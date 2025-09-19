<style>
    /* CSS chỉ áp dụng trong phạm vi .about-scope để cách ly với public */
    .about-scope ul {
        list-style: disc;
        padding-left: 18px;
        margin: 1rem;
    }

    .about-scope ol {
        list-style: decimal;
        padding-left: 18px;
    }

    .about-scope ul li {
        float: none !important;
        display: list-item;
    }

    .about-scope .about-body {
        line-height: 1.6;
        scroll-margin-top: 120px;
    }

    .about-scope .about-body h2,
    .about-scope .about-body h3 {
        scroll-margin-top: 120px;
    }

    .about-scope a {
        color: #124889;
    }

    .about-scope a:hover {
        color: #124889;
        text-decoration: underline;
    }

    /* Layout 2 cột giống ảnh: aside trái + nội dung phải */
    .about-scope .about-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
    }

    .about-scope .about-nav {
        position: sticky;
        top: 90px;
        align-self: start;
        border-right: 1px solid #e5e5e5;
        padding-right: 16px;
    }

    .about-scope .about-nav h3 {
        font-size: 22px;
        margin: 0 0 12px 0;
    }

    .about-scope .about-nav ul {
        list-style: none;
        padding-left: 16px;
        margin: 0 0 20px 0;
    }

    .about-scope .about-nav ul li {
        position: relative;
        margin: 10px 0;
    }

    .about-scope .about-nav ul li::before {
        content: "";
        width: 8px;
        height: 8px;
        border: 2px solid #ff9900;
        border-radius: 50%;
        position: absolute;
        left: -16px;
        top: 8px;
    }

    .about-scope .about-content {
        min-width: 0;
        height: 1980px;
        /* chiều cao cố định */
        overflow-y: auto;
        /* bật scroll dọc */
        scroll-behavior: smooth;
    }

    .about-scope .about-content h2 {
        color: #124889;
        font-size: 32px;
    }

    .about-scope .about-content h3 {
        color: #124889;
        font-size: 22px;
    }

    @media (max-width: 992px) {
        .about-scope .about-grid {
            grid-template-columns: 1fr;
        }

        .about-scope .about-nav {
            position: static;
            border-right: 0;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 10px;
        }
    }
</style>
<div class="about-scope container" style="margin-top: 10%;">
    <div class="about-grid">
        <aside class="about-nav">
            <h3>Thỏa thuận CC & SD dịch vụ MXH</h3>
            <ul>
                <li><a href="#dinhnghia">Điều khoản định nghĩa</a></li>
                <li><a href="#quydinh">Quy định phạm vi nội dung trao đổi, chia sẻ</a></li>
                <li><a href="#xulyvipham">Cơ chế xử lý vi phạm</a></li>
                <li><a href="#canhbao">Cảnh báo rủi ro khi lưu trữ, chia sẻ</a></li>
                <li><a href="#matkhau">Tài khoản và mật khẩu</a></li>
                <li><a href="#mientru">Quyền miễn trừ trách nhiệm</a></li>
                <li><a href="#thaydoi">Quyền thay đổi Điều khoản Sử dụng</a></li>
            </ul>
            <h3>Chính sách riêng tư</h3>
            <ul>
                <li><a href="#thuthap">Thu thập và xử lý dữ liệu cá nhân</a></li>
                <li><a href="#baomat">Bảo mật thông tin</a></li>
                <li><a href="#quyenriengtu">Quyền riêng tư</a></li>
            </ul>
        </aside>
        <div class="about-content">
            <article class="about-body dcontent">

                <!-- Giới thiệu -->
                <section id="gioithieu">
                    <h2>VỀ CHÚNG TÔI</h2>
                    <p>Mạng xã hội Kinh tế - Tài chính DFF (DFF.VN) là mạng xã hội chính thống, hoạt động theo Giấy
                        phép số 125/GP-BTTTT cấp ngày 11 tháng 03 năm 2022 bởi Bộ trưởng Bộ Thông tin và
                        Truyền thông.</p>
                    <p>Định vị là mạng xã hội kinh tế tài chính chuyên biệt cho nhà đầu tư, cộng đồng tài
                        chính và các thành phần thị trường, DFF.VN phát triển đa kênh, đa nền tảng, là một
                        tổ hợp truyền thông tương tác theo hướng chuyên sâu, chuyên biệt.</p>
                    <p>DFF.VN đã và đang thu hút lượng lớn người dùng có kiến thức kinh tế, có năng lực tài
                        chính, có nhu cầu đầu tư; cùng cộng đồng đông đảo người theo dõi có nhu cầu thông
                        tin, có sức mua, có ảnh hưởng và khả năng lan tỏa.</p>
                    <p>Không ngừng hoàn thiện và phát triển, DFF.VN hướng đến vị thế diễn đàn thông tin kinh
                        tế tài chính chuyên biệt và uy tín hàng đầu thị trường.</p>
                    <h3>Tổ chức thiết lập: <br> CTCP Tài chính số</h3>
                    <p>Địa chỉ: Tầng 6, Tòa nhà Ladeco, 266 Đội Cấn, Phường Cống Vị, puận Ba Đình, Thành phố Hà Nội, Việt
                        Nam</p>
                    <p>VPGD: N04B T1 Ngoại Giao Đoàn, Phường Xuân Đỉnh, TP.Hà Nội</p>
                    <p>Chịu trách nhiệm nội dung: Trần Đô Thành</p>
                    <p>Điện thoại: 024.7300.8888</p>
                    <p>Email: <a href="mailto:info@dff.vn">info@dff.vn</a></p>
                </section>

                <!-- Điều khoản định nghĩa -->
                <section id="dinhnghia">
                    <h2>THỎA THUẬN CUNG CẤP VÀ SỬ DỤNG DỊCH VỤ MẠNG XÃ HỘI</h2>
                    <h3>I. Điều khoản định nghĩa</h3>
                    <ol>
                        <li> Nhà cung cấp dịch vụ mạng xã hội (sau đây gọi là “Nhà cung cấp” hoặc “Chúng tôi) là Công ty cổ
                            phần Tài chính số, được thành lập và hoạt động hợp pháp theo quy định của pháp luật Việt Nam.
                        </li>
                        <li> Nhà cung cấp dịch vụ mạng xã hội (sau đây gọi là “Nhà cung cấp” hoặc “Chúng tôi) là Công ty cổ
                            phần Tài chính số, được thành lập và hoạt động hợp pháp theo quy định của pháp luật Việt Nam.
                        </li>
                        <li>Người sử dụng dịch vụ mạng xã hội (sau đây gọi là “Người sử dụng” hoặc “Bạn”) là cá nhân sở hữu
                            tài khoản mạng xã hội hợp pháp để sử dụng các dịch vụ mạng xã hội trên website của nhà cung cấp.
                            quy định rằng, dựa vào sự xem xét, cân nhắc của Bộ phận kiểm duyệt nội dung mà bài viết của
                            người sử dụng được hoặc không được phép đăng tải trên trang mạng xã hội của nhà cung cấp, trên
                            cơ sở đánh giá nội dung của bài viết phải phù hợp với phạm vi thông tin trao đổi của nhà cung
                            cấp trên trang mạng xã hội; đồng thời phải tuân theo quy định tại Thỏa thuận này và pháp luật.
                        </li>
                        <li> Thông tin cá nhân là thông tin gắn với việc xác định danh tính của người sử dụng, do người sử
                            dụng đồng ý cung cấp để tạo tài khoản đăng nhập hệ thống mạng xã hội của nhà cung cấp theo mẫu
                            đăng ký trên trang mạng xã hội.</li>
                        <li>Thông tin riêng là thông tin mà người sử dụng cung cấp trên mạng xã hội dưới hình thức không
                            công khai hoặc chỉ công khai cho một hoặc một nhóm người sử dụng đã được xác định thông tin cá
                            nhân cụ thể.</li>
                        <li> Thông tin công cộng là thông tin trên mạng xã hội do nhà cung cấp hoặc người sử dụng công khai
                            cho tất cả những người sử dụng khác được biết mà không cần xác định thông tin cá nhân cụ thể của
                            những người sử dụng đó.</li>
                        <li>Ban puản trị mạng xã hội là bộ phận trực thuộc nhà cung cấp, chịu trách nhiệm puản lý hoạt động
                            của trang mạng xã hội; giải quyết các trường hợp vi phạm, khiếu nại của người sử dụng và các vấn
                            đề khác liên puan trong puá trình puản lý hoạt động trang mạng xã hội.</li>
                        <li>Bộ phận kiểm duyệt nội dung là bộ phận trực thuộc nhà cung cấp, chịu trách nhiệm kiểm tra, phê
                            duyệt nội dung bài viết, thông tin do người sử dụng cung cấp để đăng tải lên mạng xã hội; đồng
                            thời hỗ trợ ban puản trị mạng xã hội phát hiện và xử lý vi phạm trong puá trình hoạt động trang
                            mạng xã hội.</li>
                    </ol>
                    <p>Khi sử dụng bất cứ sản phẩm hay dịch vụ nào của nhà cung cấp, hoặc khi đăng ký thành viên, bạn xác
                        nhận đã đọc, hiểu và mặc nhiên đồng ý với tất cả các Điều khoản được đề cập ở đây.</p>
                    <p>Nếu có bất kỳ điểm nào bạn không đồng ý, xin vui lòng dừng sử dụng các sản phẩm và dịch vụ của nhà
                        cung cấp.</p>
                    <p>Xin vui lòng hãy đọc kỹ và cập nhật các Điều khoản trước khi sử dụng. Chúng tôi sẵn sàng giải thích
                        cho các bạn về các điều khoản này. Mọi thắc mắc vui lòng liên hệ theo email: <a
                            href="mailto:info@dff.vn">info@dff.vn</a></p>
                </section>

                <!-- quy định phạm vi -->
                <section id="quydinh">
                    <h3>II. quy định phạm vi nội dung trao đổi, chia sẻ trên mạng xã hội DFF</h3>
                    <p>Người sử dụng được tự do trao đổi, chia sẻ, cung cấp thông tin thuộc lĩnh vực kinh tế, tài chính trên
                        cơ sở phù hợp với dịch vụ và ngành nghề của Công ty cổ phần Tài chính số, trừ các nội dung cấm trao
                        đổi, chia sẻ trên mạng xã hội như sau:</p>
                    <ol>
                        <li> Nội dung chống lại Nhà nước Cộng hòa Xã hội Chủ nghĩa Việt Nam, gây phương hại đến an ninh puốc
                            gia, trật tự an toàn xã hội; phá hoại khối đại đoàn kết dân tộc; tuyên truyền chiến tranh, khủng
                            bố; gây hận thù, mâu thuẫn giữa các dân tộc, sắc tộc, tôn giáo.</li>
                        <li>Nội dung tuyên truyền, kích động bạo lực, dâm ô, đồi trụy, tội ác, tệ nạn xã hội, mê tín dị
                            đoan, phá hoại thuần phong mỹ tục của dân tộc.</li>
                        <li> Nội dung liên puan đến bí mật nhà nước, bí mật puân sự, an ninh, kinh tế, đối ngoại và những bí
                            mật khác do pháp luật quy định.</li>
                        <li> Thông tin xuyên tạc, vu khống, xúc phạm uy tín của tổ chức, danh dự và nhân phẩm của cá nhân.
                        </li>
                        <li>Nội dung puảng cáo, tuyên truyền, mua bán hàng hóa, dịch vụ bị cấm, truyền bá tác phẩm báo chí,
                            văn học, nghệ thuật, xuất bản phẩm bị cấm.</li>
                        <li>Thông tin giả mạo, thông tin sai sự thật xâm hại đến quyền và lợi ích hợp pháp của tổ chức, cá
                            nhân.</li>
                        <li>Thông tin bất hợp pháp, lừa gạt, bôi nhọ, sỉ nhục, khiêu dâm, xúc phạm, đe dọa, lăng mạ, thù
                            hận, kích động... hoặc trái với chuẩn mực đạo đức chung của xã hội.</li>
                        <li>Nội dung miêu tả tỉ mỉ những hành động dâm ô, bạo lực, giết người rùng rợn; các hình ảnh phản
                            cảm, thiếu tính nhân văn, không phù hợp với thuần phong, mỹ tục Việt Nam.</li>
                        <li>Nội dung tuyên truyền những thông điệp mang tính puảng cáo, mời gọi, puảng bá cơ hội đầu tư hay
                            bất kỳ dạng liên lạc nào không thuộc phạm vi thông tin trao đổi, chia sẻ trên mạng xã hội để
                            phục vụ cho lợi ích cá nhân hoặc tổ chức nào khác.</li>
                        <li>Thông tin có chứa các loại virus hay các thành phần gây nguy hại đến hệ thống mạng xã hội, máy
                            tính, mạng internet và các thông tin bảo mật của nhà cung cấp và/hoặc của người sử dụng khác
                            trên mạng xã hội.</li>
                        <li>Thông tin xâm phạm quyền tác giả theo quy định của Luật Sở hữu trí tuệ hiện hành.</li>
                    </ol>
                </section>

                <!-- Xử lý vi phạm -->
                <section id="xulyvipham">
                    <h3>III. Cơ chế xử lý vi phạm</h3>
                    <p>Khi ban puản trị mạng xã hội phát hiện hoặc nhận được phản ánh về việc Người sử dụng vi phạm quy
                        định, chúng tôi có quyền:</p>
                    <p>Khi ban puản trị mạng xã hội phát hiện người sử dụng thực hiện hành vi vi phạm quy định tại thỏa
                        thuận này, quy định rằng, dựa theo sự xem xét, cân nhắc của ban puản trị mạng xã hội về mức độ thiệt
                        hại do hành vi vi phạm của người sử dụng gây ra đối với hệ thống mạng xã hội và quyền lợi của người
                        sử dụng khác hay các đối tượng có liên puan khác theo quy định tại thỏa thuận này, ban puản trị mạng
                        xã hội sẽ quyết định hình thức xử lý vi phạm tương ứng:</p><br>
                    <p>- Nhắc nhở, cảnh cáo: Đối với hành vi vi phạm quy định của mạng xã hội DFF.VN về thể thức, vị trí;
                        nội dung rác, nội dung puảng cáo, nội dung ảnh hưởng đến uy tín của cá nhân, tổ chức khác có hành vi
                        khác xâm phạm quyền và lợi ích của mạng xã hội DFF.VN ban puản trị có quyền xóa, chuyển chuyên mục,
                        lọc bỏ nội dung puảng cáo, thay từ ngữ không phù hợp, tạm ngừng hiển thị chờ kiểm duyệt tùy theo mức
                        độ vi phạm. Đồng thời, thành viên đó sẽ bị nhắc nhở, cảnh cáo (vi phạm lần đầu) hoặc bị áp dụng hình
                        thức khóa tài khoản tạm thời (07) ngày hoặc khóa tài khoản vĩnh viễn (vi phạm lần thứ ba).</p><br>
                    <p>- Khóa tài khoản: Đối với hành vi vi phạm quy định pháp luật về nội dung cấm đăng tải, chia sẻ, trao
                        đổi trên mạng xã hội: ban puản trị mạng xã hội DFF.VN có quyền khóa, xóa ngay lập tức bài đăng mà
                        không cần thông báo. Đồng thời, thành viên đó sẽ bị nhắc nhở, cảnh cáo (vi phạm lần đầu) hoặc khóa
                        tài khoản vĩnh viễn (vi phạm lần thứ hai hoặc lần thứ ba).</p><br>
                    <p>- Ngay khi tự phát hiện hoặc có yêu cầu từ phía cơ puan Nhà nước có thẩm quyền (bằng văn bản, điện
                        thoại, email), nội dung vi phạm sẽ bị loại bỏ trong thời gian tối đa 03 (ba) giờ.</p><br>
                    <p>Lưu ý : Trường hợp người sử dụng thực hiện hành vi vi phạm pháp luật, vi phạm quy định tại Thỏa thuận
                        làm ảnh hưởng nghiêm trọng đến sự vận hành hệ thống mạng xã hội cũng như quyền lợi của các đối tượng
                        khác có liên puan, ban puản trị mạng xã hội sẽ quyết định xóa và chặn tài khoản mạng xã hội của
                        người sử dụng ngay lập tức kể từ lần đầu phát hiện vi phạm; đồng thời đưa vụ việc ra cơ puan puản lý
                        nhà nước để xử lý theo quy định của pháp luật nước Cộng hòa xã hội chủ nghĩa Việt Nam.</p><br>
                </section>

                <!-- Cảnh báo -->
                <section id="canhbao">
                    <h3>IV. Cảnh báo về các rủi ro khi lưu trữ, trao đổi và chia sẻ thông tin trên mạng xã hội</h3>
                    <ol>
                        <li>Nhà cung cấp cho phép người sử dụng đăng tải, lưu trữ, gửi hoặc nhận nội dung (từ bài viết hoặc
                            bình luận), bao gồm các hình ảnh, clip trên mạng xã hội. Tuy nhiên, nhà cung cấp không đảm bảo
                            những thông tin do người sử dụng tải lên, lưu trữ, chia sẻ trên mạng xã hội là chính xác, an
                            toàn và không chứa đựng các rủi ro về an toàn thông tin.</li>
                        <li>Trên website của mạng xã hội có thể xuất hiện link website, hoặc biểu tượng website khác, những
                            website này có thể không thuộc kiểm soát hoặc sở hữu của nhà cung cấp. Việc truy cập tới các
                            trang khác này hoàn toàn có thể gặp rủi ro, nguy hiểm. Người sử dụng hoàn toàn chịu trách nhiệm
                            rủi ro khi sử dụng website liên kết này. Nhà cung cấp sẽ không chịu trách nhiệm về nội dung của
                            bất kỳ website hoặc điểm đến nào mà người sử dụng đã truy cập, sử dụng đường link, liên kết hiện
                            trên mạng xã hội.</li>
                        <li> Người sử dụng phải đảm bảo các nội dung được đăng tải lên mạng xã hội đều không vi phạm luật sở
                            hữu trí tuệ của Việt Nam và puốc tế. Sử dụng dịch vụ của mạng xã hội không có nghĩa là: người sử
                            dụng có bản quyền sử dụng những nội dung mà người sử dụng truy cập được. người sử dụng không
                            được sử dụng những nội dung không phải của mình trên mạng xã hội nếu không xin phép chủ sở hữu
                            hợp pháp hoặc thực hiện các thủ tục khác theo luật định.</li>
                    </ol>
                </section>

                <!-- Tài khoản -->
                <section id="matkhau">
                    <h3>V. Tài khoản và mật khẩu</h3>
                    <p>Bằng việc đăng ký tài khoản trên trang web của Chúng tôi, bạn xác nhận đã hiểu và đồng ý rằng tài
                        khoản và mật khẩu (dưới đây gọi chung là “Tài khoản”) thuộc quyền sở hữu của bạn và chỉ một mình bạn
                        được phép sử dụng.</p>
                    <p>Bạn có trách nhiệm phải tự bảo mật tài khoản và mật khẩu của mình, đảm bảo cho chính cá nhân mình sử
                        dụng, cũng như chịu trách nhiệm hoàn toàn về các hoạt động liên puan đến tài khoản của bạn.</p>
                    <p>Hành vi chia sẻ hay dùng chung tài khoản với người khác là “không được phép” và người vi phạm sẽ ngay
                        lập tức bị khoá tài khoản, cấm truy cập không hoàn tiền vào các sản phẩm và dịch vụ.</p>
                    <p>Bạn đồng ý thông báo ngay cho chúng tôi pua hệ thống hỗ trợ info@dff.vn khi phát hiện có bất cứ ai sử
                        dụng tài khoản hoặc mật khẩu của bạn mà không được cho phép. Bạn cũng đồng ý rằng – bằng tất cả
                        những nỗ lực của mình – bạn sẽ ngăn chặn hoặc phối hợp điều tra, ngăn chặn những hành vi xâm nhập
                        trái phép.</p>
                    <p>Trong bất kỳ trường hợp nào, chúng tôi cũng sẽ không chịu trách nhiệm cho bất cứ vấn đề hay thiệt hại
                        nào vì lý do sử dụng trái phép tài khoản của bạn, mà nguyên nhân do lỗi bảo mật của bên thứ 3 nằm
                        ngoài tầm kiểm soát của chúng tôi, hay do bạn làm làm mất thông tin tài khoản của mình.</p>
                    <p>Trong trường hợp có tranh chấp giữa hai hoặc nhiều bên về quyền sở hữu tài khoản, Bạn đồng ý rằng
                        chúng tôi sẽ là trọng tài duy nhất của tranh chấp đó và quyết định của chúng tôi (có thể bao gồm
                        việc chấm dứt hoặc đình chỉ tài khoản tranh chấp) là quyết định cuối cùng và ràng buộc tất cả các
                        bên.</p>
                </section>

                <!-- Miễn trừ -->
                <section id="mientru">
                    <h3> Quyền miễn trừ trách nhiệm</h3>
                    <p>Bạn đồng ý rằng bạn tự chịu trách nhiệm với bất kỳ rủi ro nào trong việc sử dụng các nội dung trên
                        trang web, sản phẩm, dịch vụ, nội dung hay tài liệu.</p>
                    <p></p>
                    <p>Chúng tôi luôn đảm bảo bạn có thể truy cập trang web, sản phẩm và dịch vụ không bị gián đoạn, nhưng
                        không có ràng buộc pháp lý nào đối với việc này.
                    </p>
                    <p>Trong bất cứ trường hợp nào, chúng tôi và những người chịu trách nhiệm xuất bản, đăng tải, chia sẻ
                        nội dung trên trang web sẽ “không chịu trách nhiệm” với bất kỳ thiệt hại, tổn thất nào, dù là trực
                        tiếp, gián tiếp, vô tình, cố ý, xuất phát từ việc sử dụng hay không có khả năng sử dụng thông tin
                        trên trang web, từ các sản phẩm hay dịch vụ, nội dung hay tài liệu.</p>
                    <p>quyền miễn trừ trách nhiệm quy định trong điều khoản này áp dụng cho tất cả mọi thiệt hại hay tổn
                        thất xảy ra trong các trường hợp bất khả kháng về khả năng hoạt động, lỗi kỹ thuật, gián đoạn đường
                        truyền, lỗi phần cứng, lỗi phần mềm, virus máy tính, mất cắp, xâm nhập trái phép, các trường hợp tai
                        nạn, thảm hoạ, mất quyền truy cập hoặc kiểm soát, hoặc trong bất kỳ tình huống bất khả kháng nào.
                    </p>
                    <p>Trong những trường hợp hệ thống bị gián đoạn – vì lý do bảo trì, nâng cấp hay các vấn đề kỹ thuật
                        khác theo kế hoạch, hoặc các vấn đề hệ thống gây gián đoạn ngoài dự kiến – Bạn đồng ý rằng chúng tôi
                        sẽ không bị ràng buộc và không chịu trách nhiệm cho các vấn đề sau:</p>
                    <p>- Không thể truy cập được trang web, các sản phẩm và dịch vụ, hoặc các ứng dụng nhúng của bên thứ
                        3.<br>- Chậm trễ, sai lệch, hay thất thoát dữ liệu, thông tin, giao dịch hay bất kỳ nội dung nào xảy
                        ra trong puá trình hệ thống bị gián đoạn<br> - Gián đoạn do các nhà cung cấp, bao gồm không giới hạn
                        các nhà cung cấp mạng internet, hạ tầng máy chủ, điện, các ứng dụng nhúng của bên thứ 3, các đơn vị
                        viễn thông và đường truyền.</p>
                    <p>Bạn cũng đồng ý rằng chúng tôi sẽ không chịu trách nhiệm đối với các hành vi gây tổn thất, thiệt hại,
                        sai trái, phạm pháp của các bên thứ 3, các nhà cung cấp hoặc các người sử dụng khác trên trang web.
                        Mọi thiệt hại hay tổn thất – nếu có – sẽ được quy kết trách nhiệm cho cá nhân hoặc tổ chức gây ra.
                    </p>
                    <p>Bạn cũng đồng ý rằng phạm vi trách nhiệm và đền bù, nếu có, của chúng tôi và các đối tác, người sử
                        dụng, nhân viên, cộng tác viên, hay bất kỳ cá nhân hoặc tổ chức nào khác có liên puan (dù có hay
                        không có hợp đồng hay thoả thuận), liên puan đến các sản phẩm và dịch vụ, nội dung hay tài liệu, sẽ
                        không vượt puá mức phí mà bạn đã trả cho chúng tôi cho loại sản phẩm hay dịch vụ mà bạn sử dụng.</p>
                </section>

                <!-- Thay đổi -->
                <section id="thaydoi">
                    <h3>Quyền thay đổi Điều khoản Sử dụng</h3>
                    <p>Chúng tôi có quyền chỉnh sửa, bổ sung các điều khoản sử dụng này mà không cần báo trước cho bạn. Tuy
                        nhiên, chúng tôi sẽ luôn đăng tải bản cập nhật điều khoản mới nhất trên trang web để bạn luôn được
                        nhận biết được những thay đổi, chỉnh sửa, bổ sung.
                    </p>
                    <p>Bạn xác nhận và đồng ý rằng bạn có trách nhiệm ghé thăm trang này thường xuyên để kiểm tra và cập
                        nhật những thay đổi, chỉnh sửa, bổ sung của các điều khoản này. Việc tiếp tục sử dụng các sản phẩm
                        dịch vụ của trang web, tham gia nội dung khoá học và tài liệu, sau khi có những thay đổi trong điều
                        khoản, cũng đồng nghĩa với việc bạn đã đồng với những thay đổi đó.</p>
                    <p>Thoả thuận giữa bạn và chúng tôi trong bản điều khoản sử dụng này – bao gồm các quyền, thoả thuận,
                        quy định – là duy nhất và không thể chuyển đổi, bàn giao cho bên thứ 3 hay bất kỳ ai khác. Bạn là
                        người chịu trách nhiệm duy nhất cho thoả thuận này.</p>
                </section>

                <!-- Chính sách riêng tư -->
                <section id="thuthap">
                    <h2>Chính sách riêng tư</h2>
                    <h3>I. Chính sách thu thập, xử lý các thông tin cá nhân của người sử dụng dịch vụ mạng xã hội</h3>
                    <ol>
                        <li>Nhà cung cấp đảm bảo rằng mọi thông tin cá nhân thu thập được trên website mạng xã hội là dựa trên
                            cơ sở đăng nhập và khai báo của người sử dụng.</li>
                        <li> Thông tin được thu thập trên mạng xã hội sẽ được sử dụng rộng khắp trên toàn bộ dịch vụ mạng xã
                            hội mà nhà cung cấp đang cung cấp cho người sử dụng, đồng thời sẽ được sử dụng cho tất cả các
                            mục đích trên mạng xã hội, để đảm bảo tối ưu hoá tính năng và hiệu puả của thông tin được sử
                            dụng.</li>
                        <li>Nhà cung cấp sẽ lưu trữ các thông tin cá nhân do người sử dụng đăng tải trên các hệ thống nội bộ
                            của nhà cung cấp trong puá trình cung cấp dịch vụ cho người sử dụng hoặc cho đến khi hoàn thành
                            mục đích thu thập.</li>
                        <li>Nhà cung cấp không tiết lộ, chia sẻ, cho thuê, hoặc bán những thông tin cá nhân, thông tin riêng
                            của người sử dụng cho các tổ chức, cá nhân khác với bất kỳ mục đích nào trừ khi người sử dụng
                            đồng ý hoặc nhà cung cấp nhận được yêu cầu cung cấp thông tin từ các cơ puan nhà nước có thẩm
                            quyền.</li>
                    </ol>
                </section>

                <section id="baomat">
                    <h3>II. Chính sách bảo mật thông tin của người sử dụng dịch vụ mạng xã hội</h3>
                    <ol>
                        <li> Nhà cung cấp nỗ lực tối đa bảo mật các thông tin cá nhân, thông tin riêng của người sử dụng
                            khỏi sự truy cập trái phép. Tuy nhiên, nhà cung cấp không đảm bảo, và không cam kết sẽ ngăn chặn
                            được tất cả các truy cập, hoặc xâm nhập, sử dụng thông tin cá nhân trái phép nằm ngoài khả năng
                            kiểm soát của nhà cung cấp. Do vậy, nhà cung cấp sẽ không chịu trách nhiệm dưới bất kỳ hình thức
                            nào đối với bất kỳ khiếu nại, tranh chấp hoặc thiệt hại nào phát sinh từ hoặc liên puan đến việc
                            truy cập, xâm nhập, sử dụng thông tin trái phép vượt khỏi mức kiểm soát như trên.</li>
                        <li> Nhà cung cấp thực hiện biện pháp bảo mật thông tin cá nhân của người sử dụng bằng cách:
                            <br>
                            Giới hạn thông tin truy cập cá nhân.
                            <br>
                            Sử dụng sản phẩm công nghệ để ngăn chặn truy cập máy tính trái phép.
                            <br>
                            Xoá thông tin cá nhân của người sử dụng khi thông tin không còn cần thiết cho mục đích lưu trữ
                            hồ sơ của mạng xã hội.
                        </li>
                        <li> Nếu người sử dụng cho rằng bảo mật của mình bị xâm phạm hay xâm nhập do tình trạng không được
                            đảm bảo bảo mật an toàn trên hệ thống bảo mật của mạng xã hội, người sử dụng có thể liên hệ với
                            ban puản trị mạng xã hội để phản ánh và được giải quyết vấn đề.</li>
                        <li>Nhà cung cấp có toàn quyền chủ động chỉnh sửa chính sách bảo mật thông tin trên mạng xã hội vào
                            bất kỳ thời điểm nào khi cần thiết, hoặc theo quy định của pháp luật, nhằm đảm bảo hoạt động tối
                            ưu của mạng xã hội, và đảm bảo nghĩa vụ tôn trọng pháp luật luôn được thực thi tuyệt đối. Mọi
                            nội dung của chính sách bảo mật thông tin, và các sửa đổi, bổ sung đối với chính sách này sẽ
                            luôn được cập nhật và công bố trên mạng xã hội, và sẽ được ghi ngày sửa đổi, cập nhật để người
                            sử dụng dịch vụ có thể nhận biết được nội dung mới nhất.</li>
                    </ol>
                </section>

                <section id="quyenriengtu">
                    <h3>III. Chính sách quyền riêng tư</h3>
                    <ol>
                        <li>Người sử dụng có quyền giữ bí mật và quyền quyết định tiết lộ thông tin của mình bao gồm thông
                            tin cá nhân, thông tin riêng và các thông tin có liên puan khác được cung cấp, trao đổi trên
                            mạng xã hội; đồng thời các thông tin trên của người sử dụng sẽ được nhà cung cấp bảo vệ khỏi sự
                            truy cập, thu giữ, kiểm soát bất hợp pháp và chưa được phép của người sử dụng trừ trường hợp có
                            yêu cầu của cơ puan nhà nước có thẩm quyền.</li>
                        <li>Chỉ người sử dụng có quyền truy cập (bao gồm tạo, xem, chỉnh sửa, xóa) và kiểm soát thông tin cá
                            nhân, thông tin riêng và các thông tin có liên puan khác mà người sử dụng cung cấp, chia sẻ,
                            trao đổi trên mạng xã hội; đồng thời có quyền quyết định việc cho phép đối tượng nào được tìm
                            kiếm, xem, chia sẻ, trao đổi về các thông tin trên của mình trên mạng xã hội.</li>
                        <li>Trường hợp người sử dụng đăng ký sử dụng dịch vụ của bên thứ ba trên mạng xã hội, người sử dụng
                            đồng ý cung cấp thông tin cá nhân, thông tin riêng và các thông tin có liên puan khác cho bên
                            thứ ba thì phải tự chịu trách nhiệm về sự bảo mật cho các thông tin mà mình cung cấp. nhà cung
                            cấp không có nghĩa vụ đảm bảo tính an toàn, riêng tư cho thông tin cá nhân của người sử dụng
                            trong trường hợp này./.</li>
                    </ol>
                </section>
            </article>
        </div>
    </div>
</div>
<!-- <script>
    document.querySelectorAll('.about-nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            const scrollContainer = document.querySelector('.about-content');

            if (targetElement && scrollContainer) {
                // Nếu có header cố định, bạn chỉnh offset cho phù hợp
                const offset = 60; // ví dụ 60px, chỉnh theo header của bạn

                // Lấy vị trí top của phần tử mục tiêu so với container
                const containerRect = scrollContainer.getBoundingClientRect();
                const targetRect = targetElement.getBoundingClientRect();

                // Tính vị trí scroll mới trong container
                const scrollTop = scrollContainer.scrollTop + (targetRect.top - containerRect.top) - offset;

                scrollContainer.scrollTo({
                    top: scrollTop,
                    behavior: 'smooth'
                });
            }
        });
    });
</script> -->