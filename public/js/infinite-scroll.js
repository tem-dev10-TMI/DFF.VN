
function setupInfiniteScroll(options) {
    const {
        listElementId,
        loadingElementId,
        loadMoreContainerId,
        loadMoreBtnId,
        apiUrl,
        initialOffset = 5,
        limit = 5,
        renderItemFunction,
        onNoMoreItems = () => {}
    } = options;

    let offset = initialOffset;
    let isLoading = false;

    const listEl = document.getElementById(listElementId);
    const loadingEl = document.getElementById(loadingElementId);
    const loadMoreContainer = document.getElementById(loadMoreContainerId);
    const loadMoreBtn = document.getElementById(loadMoreBtnId);

    if (!listEl || !loadingEl) {
        // console.error("InfiniteScroll: Required elements (list or loading) not found.");
        return;
    }

    const isMobile = window.innerWidth < 768;

    function loadMore() {
        if (isLoading) return;
        isLoading = true;
        if(loadingEl) loadingEl.style.display = 'block';
        if (isMobile && loadMoreContainer) {
            loadMoreContainer.style.display = 'none';
        }

        const fullApiUrl = `${apiUrl}${apiUrl.includes('?') ? '&' : '?'}offset=${offset}&limit=${limit}`;

        fetch(fullApiUrl)
            .then(r => {
                if (!r.ok) {
                    throw new Error(`HTTP error! status: ${r.status}`);
                }
                return r.json();
            })
            .then(data => {
                if (data.success && Array.isArray(data.items) && data.items.length > 0) {
                    data.items.forEach(item => {
                        const itemElement = renderItemFunction(item);
                        if (itemElement) {
                            listEl.appendChild(itemElement);
                        }
                    });
                    offset += data.items.length;

                    var dropdownElementList = [].slice.call(listEl.querySelectorAll('.dropdown-toggle'));
                    dropdownElementList.map(function(dropdownToggleEl) {
                        return new bootstrap.Dropdown(dropdownToggleEl);
                    });

                } else {
                    if (!isMobile) {
                        window.removeEventListener('scroll', handleScroll);
                    }
                    if(loadMoreContainer) loadMoreContainer.style.display = 'none';
                    if(loadingEl) {
                        loadingEl.innerHTML = '<em>Không còn bài viết nào.</em>';
                        loadingEl.style.display = 'block';
                    }
                    onNoMoreItems();
                }
            })
            .catch(error => {
                console.error('Error loading more items:', error);
                if(loadingEl) {
                    loadingEl.innerHTML = '<em>Đã có lỗi xảy ra khi tải bài viết.</em>';
                    loadingEl.style.display = 'block';
                }
            })
            .finally(() => {
                isLoading = false;
                if (loadingEl && loadingEl.innerHTML.includes('Đang tải thêm')) {
                    loadingEl.style.display = 'none';
                    if (isMobile && loadMoreContainer) {
                        loadMoreContainer.style.display = 'block';
                    }
                }
            });
    }

    const handleScroll = () => {
        const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 300;
        if (nearBottom) {
            loadMore();
        }
    };

    if (isMobile) {
        if (loadMoreContainer && loadMoreBtn) {
            loadMoreContainer.style.display = 'block';
            loadMoreBtn.addEventListener('click', loadMore);
        }
    } else {
        window.addEventListener('scroll', handleScroll);
    }
}
