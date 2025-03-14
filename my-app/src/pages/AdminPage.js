import React from 'react';
import { Outlet } from 'react-router-dom'; // Thêm Outlet để hiển thị nội dung của các tuyến đường con

import '../bootstrap/dist/css/adminlte.min.css';
import '../bootstrap/plugins/fontawesome-free/css/all.min.css';
import '../bootstrap/plugins/jquery/jquery.min';
import '../bootstrap/plugins/bootstrap/js/bootstrap.bundle.min';
import '../bootstrap/dist/js/adminlte.min';
import '../css/adminpage.css';
function AdminPage() {
    const handleLogout = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                },
            });
    
            if (!response.ok) {
                throw new Error('Đăng xuất thất bại');
            }
    
            // Xóa token và thông tin người dùng trong localStorage
            localStorage.removeItem('token');
            localStorage.removeItem('user');
    
            // Tải lại trang
            window.location.reload();  // Tải lại trang ngay lập tức
        } catch (error) {
            console.error(error);
            alert('Đăng xuất thất bại');
        }
    };
    return (
        <div className="wrapper admin-page" style={{ paddingTop: '0px' }}>

            {/* Thanh điều hướng (Navbar) */}
            
            <nav className="main-header navbar navbar-expand navbar-white navbar-light">
                <ul className="navbar-nav">
                    <li className="nav-item">
                        <a className="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i className="fas fa-bars"></i>
                        </a>
                    </li>
                    <li className="nav-item d-none d-sm-inline-block">
                        <a href="/admin" className="nav-link">Home</a>
                    </li>
                    <li className="nav-item d-none d-sm-inline-block">
                        <a href="/admin/contact" className="nav-link">Contact</a>
                    </li>
                </ul>
                <ul className="navbar-nav ml-auto">
                    <li className="nav-item">
                        <a className="nav-link" href="#">
                            <i className="far fa-user"></i> Quản lý
                        </a>
                    </li>
                    <li className="nav-item">
                            <button
                      onClick={handleLogout}
                      className="btn btn-link text-black"
                    ><i className="fas fa-power-off"></i>
                      ĐĂNG XUẤT
                    </button>
                        
                    </li>
                </ul>
            </nav>

            {/* Thanh bên (Sidebar) */}
            <aside className="main-sidebar sidebar-dark-primary elevation-4">
                <div className="sidebar">
                    {/* Thanh người dùng */}
                    <div className="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div className="image">
                            <img src="/bootstrap/dist/img/user2-160x160.jpg" className="img-circle elevation-2" alt="User Image" />
                        </div>
                        <div className="info">
                            <a href="#" className="d-block">LeTranThaiNguu</a>
                        </div>
                    </div>

                    {/* Menu bên */}
                    <nav className="mt-2">
                        <ul className="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            {/* Các mục menu */}
                            <li className="nav-item">
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Sản phẩm
                                        <i className="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    <li className="nav-item">
                                        <a href="/admin/product" className="nav-link">
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>Tất cả sản phẩm</p>
                                        </a>
                                    </li>
                                    <li className="nav-item">
                                        <a href="/admin/category" className="nav-link">
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>Danh mục</p>
                                        </a>
                                    </li>
                                    <li className="nav-item">
                                        <a href="/admin/brand" className="nav-link">
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>Thương hiệu</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li className="nav-item">
                            <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-paper-plane"></i>
                                    <p>
                                        Bài Viết
                                        <i className="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    <li className="nav-item">
                                        <a href="/admin/post" className="nav-link">
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>Tất cả bài viết</p>
                                        </a>
                                    </li>
                                    <li className="nav-item">
                                        <a href="/admin/topic" className="nav-link">
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>Chủ đề</p>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                            <li className="nav-item">
                            <a href="/admin/order" className="nav-link">
                                    <i className="nav-icon fas fa-truck text-light"></i>
                                    <p>
                                        Đơn hàng 
                                    </p>
                                </a>

                            </li>
                            <li className="nav-item">
                            <a href="/admin/contact" className="nav-link">
                                    <i className="nav-icon fas fa-address-book"></i>
                                    <p>
                                        Liên hệ 
                                    </p>
                                </a>

                            </li>
                            <li className="nav-item">
                            <a href="/admin/banner" className="nav-link">
                                    <i className="nav-icon fas fa-image"></i>
                                    <p>
                                        Banner
                                    </p>
                                </a>

                            </li>
                            <li className="nav-item">
                            <a href="/admin/user" className="nav-link">
                                    <i className="nav-icon fas fa-user-tie"></i>
                                    <p>
                                        Thành viên
                                    </p>
                                </a>

                            </li>
                            {/* Thêm các mục khác tương tự */}
                        </ul>
                    </nav>
                </div>
            </aside>

           
            <div className="">
                <Outlet /> {/* Chèn nội dung của các tuyến đường con */}
            </div>
            
            {/* Chân trang (Footer) */}
            <footer className="main-footer">
                <div className="float-right d-none d-sm-block">
                    <b>Version</b> 3.2.0
                </div>
                <strong>Sửa bởi: Lê Trần Thái Ngưu</strong> All rights reserved.
            </footer>

            {/* Thanh điều khiển bên */}
            <aside className="control-sidebar control-sidebar-dark">
            </aside>
        </div>
    );
}

export default AdminPage;
