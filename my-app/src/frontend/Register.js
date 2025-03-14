import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify'; // Import toast
import 'react-toastify/dist/ReactToastify.css'; // Import CSS cho Toast
import '../css/Register.css';

function Register() {
    const [formData, setFormData] = useState({
        name: '',
        phone: '',
        email: '',
        gender: '1',
        address: '',
        username: '',
        password: '',
        password_re: '',
        roles: 'customer',
        status: 1,
        created_by: 1,
    });

    const [errors, setErrors] = useState({});
    const [errorMessage, setErrorMessage] = useState('');
    const navigate = useNavigate();

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const validateForm = () => {
        let formErrors = {};
        if (!formData.name) formErrors.name = 'Họ tên là bắt buộc';
        if (!formData.phone) formErrors.phone = 'Điện thoại là bắt buộc';
        if (!formData.email) formErrors.email = 'Email là bắt buộc';
        if (!formData.username) formErrors.username = 'Tên đăng nhập là bắt buộc';
        if (!formData.password) formErrors.password = 'Mật khẩu là bắt buộc';
        if (formData.password !== formData.password_re) formErrors.password_re = 'Mật khẩu xác nhận không khớp';
        return formErrors;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const formErrors = validateForm();
        if (Object.keys(formErrors).length > 0) {
            setErrors(formErrors);
            return;
        }

        const postData = {
            ...formData,
            roles: 'customer',
            status: 1,
            created_by: 1,
        };

        fetch('http://localhost:8000/api/users', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(postData),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi xảy ra khi đăng ký');
            }
            return response.json();
        })
        .then(data => {
            if (data.data && data.data.id) {
                toast.success('Đăng ký thành công!'); // Hiển thị thông báo thành công

                // Sử dụng setTimeout để trì hoãn việc điều hướng
                setTimeout(() => {
                    navigate('/login'); // Chuyển đến trang đăng nhập
                }, 2000); // Trì hoãn 2 giây
            } else {
                setErrorMessage('Có lỗi xảy ra khi đăng ký');
            }
        })          
        .catch(error => {
            setErrorMessage('Lỗi khi đăng ký');
            console.error("Error:", error);
        });
    };

    return (
        <div className="register-container">
            <h2 className="register-header">Đăng ký tài khoản</h2>
            {errorMessage && <p className="text-danger">{errorMessage}</p>}
            <form onSubmit={handleSubmit} className="register-form">
                <div className="register-form-group">
                    <label htmlFor="name" className="register-label">Họ tên</label>
                    <input 
                        type="text" 
                        name="name" 
                        value={formData.name} 
                        onChange={handleChange} 
                        className="register-input" 
                    />
                    {errors.name && <p className="register-error">{errors.name}</p>}
                </div>
                <div className="register-form-group">
                    <label htmlFor="phone" className="register-label">Điện thoại</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value={formData.phone} 
                        onChange={handleChange} 
                        className="register-input" 
                    />
                    {errors.phone && <p className="register-error">{errors.phone}</p>}
                </div>
                <div className="register-form-group">
                    <label htmlFor="email" className="register-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value={formData.email} 
                        onChange={handleChange} 
                        className="register-input" 
                    />
                    {errors.email && <p className="register-error">{errors.email}</p>}
                </div>
                <div className="register-form-group">
                    <label htmlFor="gender" className="register-label">Giới tính</label>
                    <select 
                        name="gender" 
                        value={formData.gender} 
                        onChange={handleChange} 
                        className="register-input"
                    >
                        <option value="1">Nam</option>
                        <option value="0">Nữ</option>
                    </select>
                </div>
                <div className="register-form-group half-width">
                    <label htmlFor="username" className="register-label">Tên đăng nhập</label>
                    <input 
                        type="text" 
                        name="username" 
                        value={formData.username} 
                        onChange={handleChange} 
                        className="register-input" 
                    />
                    {errors.username && <p className="register-error">{errors.username}</p>}
                </div>
                <div className="register-form-group half-width">
                    <label htmlFor="password" className="register-label">Mật khẩu</label>
                    <input 
                        type="password" 
                        name="password" 
                        value={formData.password} 
                        onChange={handleChange} 
                        className="register-input" 
                    />
                    {errors.password && <p className="register-error">{errors.password}</p>}
                </div>
                <div className="register-form-group half-width">
                    <label htmlFor="password_re" className="register-label">Xác nhận mật khẩu</label>
                    <input 
                        type="password" 
                        name="password_re" 
                        value={formData.password_re} 
                        onChange={handleChange} 
                        className="register-input" 
                    />
                    {errors.password_re && <p className="register-error">{errors.password_re}</p>}
                </div>
                <button type="submit" className="register-button">Đăng ký</button>
            </form>
        </div>
    );
}

export default Register;
