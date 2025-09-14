// Global variables
let currentSymbol = 'BTCUSDT';
let currentTimeframe = '15m';
let currentProvider = 'binance'; // binance | fmp | stooq
let marketData = {};
let wsConnection = null;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    try {
        initializeApp();
    } catch (error) {
        console.error('Error initializing app:', error);
        showError('Có lỗi khi khởi tạo ứng dụng. Vui lòng tải lại trang.');
    }
});

async function initializeApp() {
    // Set current date
    updateCurrentDate();
    
    // Setup event listeners first
    setupEventListeners();
    
    // Load market data (provider-aware)
    await loadMarketData();
    
    // Initialize chart (TradingView only)
    await initializeTradingViewOnly();
    
    // Start WebSocket connection for real-time data
    startWebSocketConnection();
    
    // Update Bitcoin converter
    updateBitcoinConverter();
}

// Update current date
function updateCurrentDate() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: '2-digit', 
        day: '2-digit' 
    };
    const dateString = now.toLocaleDateString('vi-VN', options);
    document.getElementById('currentDate').textContent = dateString;
}

// Load market data from Binance API
async function loadMarketData() {
    // Crypto default list (Binance)
    const cryptoSymbols = ['SOLUSDT', 'SUIUSDT', 'ICPUSDT', 'BTCUSDT', 'AVAXUSDT', 'BNBUSDT', 'TAOUSDT', 'ADAUSDT'];
    // Stocks sample list (FMP/Stooq)
    const stockSymbols = ['AAPL', 'MSFT', 'GOOGL', 'TSLA', 'NVDA', 'AMZN'];
    
    try {
        // Add timeout to fetch request
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
        let response;
        if (currentProvider === 'binance') {
            response = await fetch('proxy.php?url=' + encodeURIComponent('https://api.binance.com/api/v3/ticker/24hr'), { signal: controller.signal });
        } else if (currentProvider === 'fmp') {
            // FinancialModelingPrep via proxy
            response = await fetch('proxy.php?url=' + encodeURIComponent('https://financialmodelingprep.com/api/v3/quote/' + stockSymbols.join(',') + '?apikey=demo'), { signal: controller.signal });
        } else if (currentProvider === 'stooq') {
            // Stooq CSV endpoint -> convert to JSON
            // We'll fetch quotes individually for reliability
            const quotes = await Promise.all(stockSymbols.map(sym => fetch('proxy.php?url=' + encodeURIComponent(`https://stooq.com/q/l/?s=${sym.toLowerCase()}&f=sd2t2ohlcv&h&e=json`))));
            const jsons = await Promise.all(quotes.map(r => r.json()));
            clearTimeout(timeoutId);
            const mapped = jsons.map(j => j.symbols[0]).filter(x => x && x.close !== 'N/D').map(x => ({
                symbol: x.symbol.toUpperCase(),
                lastPrice: String(x.close),
                priceChange: String((parseFloat(x.close) - parseFloat(x.open)).toFixed(2)),
                priceChangePercent: String(((parseFloat(x.close) - parseFloat(x.open)) / parseFloat(x.open) * 100).toFixed(2))
            }));
            // Update UI directly for Stooq and return
            mapped.forEach(updateMarketItem);
            marketData = mapped.reduce((acc, it) => { acc[it.symbol] = it; return acc; }, {});
            return;
        }
        
        clearTimeout(timeoutId);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Filter and process data
        let filteredData = [];
        if (currentProvider === 'binance') {
            filteredData = data.filter(item => cryptoSymbols.includes(item.symbol));
        } else if (currentProvider === 'fmp') {
            filteredData = data.map(i => ({
                symbol: i.symbol,
                lastPrice: String(i.price),
                priceChange: String(i.change),
                priceChangePercent: String(i.changesPercentage)
            }));
        }
        
        if (filteredData.length === 0) {
            throw new Error('No market data received');
        }
        
        // Update market items
        filteredData.forEach(item => {
            updateMarketItem(item);
        });
        
        // Store data globally
        marketData = filteredData.reduce((acc, item) => {
            acc[item.symbol] = item;
            return acc;
        }, {});
        
        console.log('Market data loaded successfully:', filteredData.length, 'symbols');
        
    } catch (error) {
        console.error('Error loading market data:', error);
        
        if (error.name === 'AbortError') {
            showError('Kết nối API quá chậm. Đang sử dụng dữ liệu mẫu...');
        } else if (error.message.includes('Failed to fetch')) {
            showError('Không thể kết nối đến Binance API. Kiểm tra kết nối internet.');
        } else {
            showError('Lỗi tải dữ liệu thị trường: ' + error.message);
        }
        
        // Use sample data as fallback
        loadSampleMarketData();
    }
}

// Load sample market data as fallback
function loadSampleMarketData() {
    const sampleData = [
        { symbol: 'BTCUSDT', lastPrice: '115368.28', priceChange: '1064.85', priceChangePercent: '0.92' },
        { symbol: 'SOLUSDT', lastPrice: '238.21', priceChange: '16.88', priceChangePercent: '7.08' },
        { symbol: 'SUIUSDT', lastPrice: '3.67', priceChange: '0.04', priceChangePercent: '1.22' },
        { symbol: 'ICPUSDT', lastPrice: '4.94', priceChange: '0.03', priceChangePercent: '0.69' },
        { symbol: 'AVAXUSDT', lastPrice: '28.82', priceChange: '-0.26', priceChangePercent: '-0.89' },
        { symbol: 'BNBUSDT', lastPrice: '908.14', priceChange: '13.45', priceChangePercent: '1.48' },
        { symbol: 'TAOUSDT', lastPrice: '357.60', priceChange: '5.59', priceChangePercent: '1.56' },
        { symbol: 'ADAUSDT', lastPrice: '0.90', priceChange: '0.02', priceChangePercent: '1.84' }
    ];
    
    sampleData.forEach(item => {
        updateMarketItem(item);
    });
    
    marketData = sampleData.reduce((acc, item) => {
        acc[item.symbol] = item;
        return acc;
    }, {});
    
    console.log('Using sample market data');
}

// Update individual market item
function updateMarketItem(data) {
    const marketItem = document.querySelector(`[data-symbol="${data.symbol}"]`);
    if (!marketItem) return;
    
    const price = parseFloat(data.lastPrice);
    const change = parseFloat(data.priceChange);
    const changePercent = parseFloat(data.priceChangePercent);
    
    // Update price
    const priceElement = marketItem.querySelector('.price');
    priceElement.textContent = formatPrice(price);
    
    // Update change
    const changeElement = marketItem.querySelector('.change');
    const isPositive = change >= 0;
    changeElement.className = `change ${isPositive ? 'positive' : 'negative'}`;
    changeElement.textContent = `${isPositive ? '+' : ''}${change.toFixed(2)} (${isPositive ? '+' : ''}${changePercent.toFixed(2)}%)`;
    
    // Add click handler
    marketItem.addEventListener('click', () => {
        selectSymbol(data.symbol);
    });
}

// Format price for display
function formatPrice(price) {
    if (price >= 1000) {
        return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    } else if (price >= 1) {
        return price.toFixed(2);
    } else {
        return price.toFixed(4);
    }
}

// Select symbol and update chart
function selectSymbol(symbol) {
    currentSymbol = symbol;
    // If user switched to stocks without suffix, normalize for chart
    if (currentProvider !== 'binance') {
        document.getElementById('symbolInfoText').textContent = `${symbol} · ${currentTimeframe} · ${currentProvider.toUpperCase()}`;
    }
    
    // Update symbol search input
    document.getElementById('symbolSearch').value = symbol;
    
    // Update chart
    updateTradingViewChart();
    
    // Update chart info
    updateChartInfo();
}

// Initialize TradingView only (no fallback)
async function initializeTradingViewOnly() {
    const chartContainer = document.getElementById('tradingview_chart');
    await loadTradingViewScript();
    if (typeof TradingView === 'undefined') {
        showError('Không tải được TradingView. Kiểm tra mạng hoặc thử lại.');
        return;
    }
    chartContainer.innerHTML = '';
    const providerPrefix = currentProvider === 'binance' ? 'BINANCE:' : (currentProvider === 'fmp' ? 'NASDAQ:' : 'NASDAQ:');
    const tvIntervalMap = { '1m': '1', '15m': '15', '30m': '30', '1h': '60' };
    const tvInterval = tvIntervalMap[currentTimeframe] || '15';
    new TradingView.widget({
        autosize: true,
        symbol: `${providerPrefix}${currentSymbol}`,
        interval: tvInterval,
        timezone: "Asia/Ho_Chi_Minh",
        theme: "dark",
        style: "1",
        locale: "vi",
        toolbar_bg: "#1a1a1a",
        enable_publishing: false,
        hide_top_toolbar: false,
        hide_legend: false,
        save_image: false,
        container_id: "tradingview_chart",
        studies: ["Volume@tv-basicstudies"],
        overrides: {
            "paneProperties.background": "#0a0a0a",
            "paneProperties.vertGridProperties.color": "#333",
            "paneProperties.horzGridProperties.color": "#333",
            "symbolWatermarkProperties.transparency": 90,
            "scalesProperties.textColor": "#ccc"
        }
    });
}

// Load TradingView script dynamically
function loadTradingViewScript() {
    return new Promise((resolve, reject) => {
        if (typeof TradingView !== 'undefined') {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://s3.tradingview.com/tv.js';
        script.onload = () => resolve();
        script.onerror = () => {
            console.warn('TradingView script failed to load');
            reject(new Error('TradingView script failed'));
        };
        document.head.appendChild(script);
    });
}

// Chart.js fallback removed per request to use TradingView only

// Update chart (works for both TradingView and fallback)
function updateTradingViewChart() {
    const chart = document.getElementById('tradingview_chart');
    if (chart) initializeTradingViewOnly();
}

// Update chart info
function updateChartInfo() {
    const data = marketData[currentSymbol];
    if (!data) return;
    
    const price = parseFloat(data.lastPrice);
    const open = parseFloat(data.openPrice);
    const high = parseFloat(data.highPrice);
    const low = parseFloat(data.lowPrice);
    const change = parseFloat(data.priceChange);
    const changePercent = parseFloat(data.priceChangePercent);
    const volume = parseFloat(data.volume);
    
    // Update symbol info
    const symbolInfo = document.querySelector('.symbol-info span');
    const providerName = currentProvider === 'binance' ? 'Binance' : (currentProvider === 'fmp' ? 'FMP' : 'Stooq');
    if (currentProvider === 'binance') {
        symbolInfo.textContent = `${currentSymbol.replace('USDT', '')} / TetherUS · ${currentTimeframe} · ${providerName}`;
    } else {
        symbolInfo.textContent = `${currentSymbol} · ${currentTimeframe} · ${providerName}`;
    }
    
    // Update OHLC
    const ohlcElements = document.querySelectorAll('.ohlc-info span');
    ohlcElements[0].textContent = `O ${formatPrice(open)}`;
    ohlcElements[1].textContent = `H ${formatPrice(high)}`;
    ohlcElements[2].textContent = `L ${formatPrice(low)}`;
    ohlcElements[3].textContent = `C ${formatPrice(price)}`;
    
    // Update price change
    const changeElement = document.querySelector('.price-change .change');
    const isPositive = change >= 0;
    changeElement.className = `change ${isPositive ? 'positive' : 'negative'}`;
    changeElement.textContent = `${change.toFixed(2)} (${isPositive ? '+' : ''}${changePercent.toFixed(2)}%)`;
    
    // Update volume
    const volumeElement = document.querySelector('.volume span');
    volumeElement.textContent = `Vol - ${currentSymbol.replace('USDT', '')} ${(volume / 1000).toFixed(0)}K`;
}

// Start WebSocket connection for real-time data
function startWebSocketConnection() {
    if (currentProvider !== 'binance') {
        // Only Binance supports our WebSocket in this demo
        return;
    }
    const symbols = ['SOLUSDT', 'SUIUSDT', 'ICPUSDT', 'BTCUSDT', 'AVAXUSDT', 'BNBUSDT', 'TAOUSDT', 'ADAUSDT'];
    const streamNames = symbols.map(symbol => `${symbol.toLowerCase()}@ticker`).join('/');
    const wsUrl = `wss://stream.binance.com:9443/ws/${streamNames}`;
    
    try {
        wsConnection = new WebSocket(wsUrl);
        
        wsConnection.onopen = function() {
            console.log('WebSocket connected');
        };
        
        wsConnection.onmessage = function(event) {
            const data = JSON.parse(event.data);
            updateRealTimeData(data);
        };
        
        wsConnection.onerror = function(error) {
            console.error('WebSocket error:', error);
        };
        
        wsConnection.onclose = function() {
            console.log('WebSocket disconnected, reconnecting...');
            setTimeout(startWebSocketConnection, 5000);
        };
        
    } catch (error) {
        console.error('Error starting WebSocket:', error);
    }
}

// Update real-time data
function updateRealTimeData(data) {
    const symbol = data.s;
    const price = parseFloat(data.c);
    const change = parseFloat(data.P);
    
    // Update market item
    const marketItem = document.querySelector(`[data-symbol="${symbol}"]`);
    if (marketItem) {
        const priceElement = marketItem.querySelector('.price');
        const changeElement = marketItem.querySelector('.change');
        
        priceElement.textContent = formatPrice(price);
        
        const isPositive = change >= 0;
        changeElement.className = `change ${isPositive ? 'positive' : 'negative'}`;
        changeElement.textContent = `${isPositive ? '+' : ''}${change.toFixed(2)}%`;
    }
    
    // Update chart info if current symbol
    if (symbol === currentSymbol) {
        updateChartInfo();
    }
    
    // Update Bitcoin converter if BTC
    if (symbol === 'BTCUSDT') {
        updateBitcoinConverter();
    }
}

// Update Bitcoin converter
function updateBitcoinConverter() {
    const btcData = marketData['BTCUSDT'];
    if (!btcData) return;
    
    const btcPrice = parseFloat(btcData.lastPrice);
    const btcAmount = parseFloat(document.getElementById('btcAmount').value) || 1;
    const usdValue = btcAmount * btcPrice;
    
    document.getElementById('btcResult').textContent = usdValue.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Setup event listeners
function setupEventListeners() {
    // Symbol search
    document.getElementById('symbolSearch').addEventListener('change', function() {
        const symbol = this.value.toUpperCase();
        if (symbol && symbol.endsWith('USDT')) {
            selectSymbol(symbol);
        }
    });
    
    // Timeframe buttons
    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentTimeframe = this.textContent;
            updateTradingViewChart();
        });
    });
    
    // Bitcoin amount input
    document.getElementById('btcAmount').addEventListener('input', updateBitcoinConverter);
    
    // Crypto search
    document.getElementById('cryptoSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        filterMarketItems(query);
    });

    // Provider/exchange select
    const providerSelect = document.getElementById('providerSelect');
    providerSelect.addEventListener('change', async function() {
        currentProvider = this.value;
        // Adjust default symbol for stocks vs crypto
        if (currentProvider === 'binance') {
            currentSymbol = 'BTCUSDT';
            document.getElementById('symbolSearch').value = currentSymbol;
        } else {
            currentSymbol = 'AAPL';
            document.getElementById('symbolSearch').value = currentSymbol;
        }
        await loadMarketData();
        await initializeTradingViewOnly();
        updateChartInfo();
        updateBitcoinConverter();
    });
    
    // Main search
    document.getElementById('mainSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        // Implement search functionality
        console.log('Searching for:', query);
    });
    
    // Scroll to top
    document.getElementById('scrollTop').addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Show/hide scroll to top button
    window.addEventListener('scroll', function() {
        const scrollTop = document.getElementById('scrollTop');
        if (window.pageYOffset > 300) {
            scrollTop.style.display = 'block';
        } else {
            scrollTop.style.display = 'none';
        }
    });
    
    // Comment input
    const commentInput = document.querySelector('.comment-input input');
    commentInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            addComment(this.value.trim());
            this.value = '';
        }
    });
}

// Filter market items
function filterMarketItems(query) {
    const marketItems = document.querySelectorAll('.market-item');
    
    marketItems.forEach(item => {
        const symbol = item.querySelector('.symbol').textContent.toLowerCase();
        if (symbol.includes(query)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Add comment
function addComment(text) {
    const commentsList = document.getElementById('commentsList');
    const commentDiv = document.createElement('div');
    commentDiv.className = 'comment-item';
    commentDiv.innerHTML = `
        <div class="comment-header">
            <div class="user-avatar-small">A</div>
            <span class="username">Anonymous</span>
            <span class="comment-time">${new Date().toLocaleTimeString('vi-VN')}</span>
        </div>
        <div class="comment-text">${text}</div>
    `;
    
    commentsList.appendChild(commentDiv);
    commentsList.scrollTop = commentsList.scrollHeight;
}

// Show error message
function showError(message) {
    // Create error notification
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-notification';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background-color: #ff4444;
        color: white;
        padding: 15px 20px;
        border-radius: 6px;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .comment-item {
        background-color: #2a2a2a;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #333;
    }
    
    .comment-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    
    .username {
        font-weight: 500;
        color: #007bff;
    }
    
    .comment-time {
        font-size: 12px;
        color: #666;
        margin-left: auto;
    }
    
    .comment-text {
        color: #ccc;
        line-height: 1.4;
    }
`;
document.head.appendChild(style);

// Handle page visibility change
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        if (wsConnection) {
            wsConnection.close();
        }
    } else {
        if (!wsConnection || wsConnection.readyState === WebSocket.CLOSED) {
            startWebSocketConnection();
        }
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (wsConnection) {
        wsConnection.close();
    }
});
