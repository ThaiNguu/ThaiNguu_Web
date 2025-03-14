import React, { useState } from 'react';
import axios from 'axios';
import '../css/login.css';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const [userImage, setUserImage] = useState(''); // Thêm state để lưu trữ ảnh

    const handleLogin = async (e) => {
        e.preventDefault(); 
        try {
            const response = await axios.post('http://localhost:8000/api/login', { email, password });
            console.log(response.data);
            const { token, user } = response.data;
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            setUserImage(user.image); // Lưu ảnh vào state

            // Chuyển hướng đến trang khác (có thể là trang chính)
            window.location.replace('/'); 
            
        } catch (error) {
            setError('Tài khoản hoặc mật khẩu không đúng');
        }
    };

    return (
        <div className="login-custom-container">
            <h2 className="login-title">Đăng Nhập</h2>
            {error && <p className="login-error-message">{error}</p>}
            <form onSubmit={handleLogin} className="login-form-custom">
                <div className="login-form-group">
                    <label htmlFor="login-email">Email</label>
                    <input 
                        id="login-email"
                        type="email" 
                        className="login-input" 
                        value={email} 
                        onChange={(e) => setEmail(e.target.value)} 
                        required 
                    />
                </div>
                <div className="login-form-group">
                    <label htmlFor="login-password">Mật khẩu</label>
                    <input 
                        id="login-password"
                        type="password" 
                        className="login-input" 
                        value={password} 
                        onChange={(e) => setPassword(e.target.value)} 
                        required 
                    />
                </div>
                <button type="submit" className="login-submit-btn">Đăng Nhập</button>
            </form>
            {/* Hiển thị ảnh nếu có */}
            {userImage && (
                <div className="user-image-container">
                    <img src={`path/to/images/${userImage}`} alt="User" className="user-image" />
                </div>
            )}
        </div>
    );
};

export default Login;
