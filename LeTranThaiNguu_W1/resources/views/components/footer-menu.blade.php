<div id="footer-container" class="d-flex justify-content-between align-items-center">
    <div class="footer-left">
        @ Bản quyền thuộc về <a href="{{ asset('/') }}">Thái Ngưu</a> All rights reserved
    </div>
    <div id="footer-menu" class="d-flex flex-wrap justify-content-center">
        @foreach ($list_menu as $row_menu)
            <x-footer-menu-item :rowmenu="$row_menu"/>
        @endforeach
    </div>
    <div class="footer-right">
        <img src="{{ asset('images/footer.png') }}" alt="Footer Image">
    </div>
    
</div>
