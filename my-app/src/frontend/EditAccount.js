import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import MainMenu from '../components/main-menu';
import Footer from '../components/Footer';

function EditAccount() {
  const { id } = useParams();
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    email: '',
    gender: '1',
    address: '',
    username: '',
    password: '',
    password_re: '',
    roles: 'customer', // Giữ lại nhưng không hiển thị
    status: '2', // Giữ lại nhưng không hiển thị
    image: null,
  });
  const [errors, setErrors] = useState({});
  const [errorMessage, setErrorMessage] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null); // Thêm trạng thái thông báo thành công
  const navigate = useNavigate();

  // Lấy thông tin người dùng để chỉnh sửa
  useEffect(() => {
    fetch(`http://localhost:8000/api/users/${id}`)
      .then(response => response.json())
      .then(data => {
        setFormData({
          name: data.name || '',
          phone: data.phone || '',
          email: data.email || '',
          gender: data.gender || '1',
          address: data.address || '',
          username: data.username || '',
          roles: data.roles || 'customer', 
          status: data.status || '2', 
          password: '',
          password_re: '',
          image: null,
        });
      })
      .catch(error => setErrorMessage('Lỗi khi lấy dữ liệu người dùng'));
  }, [id]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    if (name === 'image') {
      setFormData({ ...formData, image: files[0] });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const validateForm = () => {
    let formErrors = {};
    if (!formData.name) formErrors.name = 'Họ tên là bắt buộc';
    if (!formData.phone) formErrors.phone = 'Điện thoại là bắt buộc';
    if (!formData.email) formErrors.email = 'Email là bắt buộc';
    if (!formData.username) formErrors.username = 'Tên đăng nhập là bắt buộc';
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

    const data = {
      name: formData.name,
      phone: formData.phone,
      email: formData.email,
      gender: formData.gender,
      address: formData.address,
      username: formData.username,
      roles: formData.roles, 
      status: formData.status, 
      password: formData.password || null,
    };

    updateUser(data);
  };

  const updateUser = (data) => {
    fetch(`http://localhost:8000/api/users/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then(response => {
        if (response.ok) {
          setSuccessMessage('Thông tin đã được cập nhật. Vui lòng đăng nhập lại.'); 
          if (formData.image) {
            uploadImage(); 
          } else {
            setTimeout(() => {
              navigate('/account'); 
            }, 2000); 
          }
        } else {
          throw new Error('Có lỗi xảy ra khi cập nhật người dùng');
        }
      })
      .catch(error => setErrorMessage(error.message));
  };

  const uploadImage = () => {
    const imageData = new FormData();
    imageData.append('image', formData.image);

    fetch(`http://localhost:8000/api/users/${id}/upload-image`, {
      method: 'POST',
      body: imageData,
    })
      .then(response => {
        if (response.ok) {
          setTimeout(() => {
            navigate('/account'); 
          }, 2000); 
        } else {
          throw new Error('Có lỗi xảy ra khi tải lên hình ảnh');
        }
      })
      .catch(error => setErrorMessage(error.message));
  };

  return (
    <><MainMenu/>
    <div>
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-12">
              <h1>Chỉnh sửa thông tin tài khoản</h1>
            </div>
            
          </div>
        </div>
      </section>
      <section className="content">
        <div className="card">
          <div className="card-header">
            <div className="row">
              <div className="col-12 text-right">
                <button type="submit" onClick={handleSubmit} className="btn btn-sm btn-success">
                  <i className="fa fa-save"></i> Lưu
                </button>
                <Link className="btn btn-sm btn-info" to="/account">
                  <i className="fa fa-arrow-left"></i> Về danh sách
                </Link>
              </div>
            </div>
          </div>
          <div className="card-body">
            {errorMessage && <p className="text-danger">{errorMessage}</p>}
            {successMessage && <p className="text-success">{successMessage}</p>} 
            <form onSubmit={handleSubmit}>
              <div className="row">
                <div className="col-md-6">
                  <div className="mb-3">
                    <label htmlFor="name">Họ tên</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" />
                    {errors.name && <p className="text-danger">{errors.name}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="phone">Điện thoại</label>
                    <input type="text" name="phone" value={formData.phone} onChange={handleChange} className="form-control" />
                    {errors.phone && <p className="text-danger">{errors.phone}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="email">Email</label>
                    <input type="email" name="email" value={formData.email} onChange={handleChange} className="form-control" />
                    {errors.email && <p className="text-danger">{errors.email}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="gender">Giới tính</label>
                    <select name="gender" value={formData.gender} onChange={handleChange} className="form-control">
                      <option value="1">Nam</option>
                      <option value="0">Nữ</option>
                    </select>
                  </div>
                  <div className="mb-3">
                    <label htmlFor="address">Địa chỉ</label>
                    <input type="text" name="address" value={formData.address} onChange={handleChange} className="form-control" />
                  </div>
                </div>
                <div className="col-md-6">
                  <div className="mb-3">
                    <label htmlFor="username">Tên đăng nhập</label>
                    <input type="text" name="username" value={formData.username} onChange={handleChange} className="form-control" />
                    {errors.username && <p className="text-danger">{errors.username}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="password">Mật khẩu</label>
                    <input type="password" name="password" value={formData.password} onChange={handleChange} className="form-control" />
                  </div>
                  <div className="mb-3">
                    <label htmlFor="password_re">Nhập lại mật khẩu</label>
                    <input type="password" name="password_re" value={formData.password_re} onChange={handleChange} className="form-control" />
                    {errors.password_re && <p className="text-danger">{errors.password_re}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="image">Hình ảnh</label>
                    <input type="file" name="image" onChange={handleChange} className="form-control" />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
    <Footer/>
    </>
  );
}

export default EditAccount;
