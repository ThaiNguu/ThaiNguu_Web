import { Navigate } from 'react-router-dom';

const ProtectedRoute = ({ children }) => {
    const token = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user')); // Lấy thông tin người dùng từ localStorage

    // Kiểm tra vai trò
    const isAdmin = user && user.roles === 'admin'; // Chỉ cho phép người dùng có vai trò admin

    // Nếu có token và vai trò là admin, cho phép truy cập; nếu không, chuyển hướng về trang login
    return token && isAdmin ? children : <Navigate to="/login" />;
};

export default ProtectedRoute;
