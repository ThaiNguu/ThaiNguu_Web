import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';

function AddBanner() {
  const [categories, setCategories] = useState([]);
  const [banners, setBanners] = useState([]);
  const [formData, setFormData] = useState({
    name: '',
    sort_order: '', // Added sort_order to formData
    description: '',
    position: '',
    link: '',
    status: '2',
    image: null,
  });
  const [errors, setErrors] = useState({});
  const [errorMessage, setErrorMessage] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    fetch('http://localhost:8000/api/banner')
        .then(response => response.json())
        .then(bannerData => setBanners(bannerData.data))
        .catch(error => setErrorMessage('Lỗi khi lấy dữ liệu banner'));
}, []);


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
    if (!formData.name) formErrors.name = 'Tên banner là bắt buộc';
    if (!formData.sort_order) formErrors.sort_order = 'Sắp xếp là bắt buộc'; // Added sort_order validation
    if (!formData.description) formErrors.description = 'Mô tả là bắt buộc';
    if (!formData.position) formErrors.position = 'Vị trí là bắt buộc';
    if (!formData.link) formErrors.link = 'Đường dẫn là bắt buộc';
    
    return formErrors;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const formErrors = validateForm();
    if (Object.keys(formErrors).length > 0) {
      setErrors(formErrors);
      return;
    }

    const bannerData = new FormData();
    for (let key in formData) {
      bannerData.append(key, formData[key]);
    }

    fetch('http://localhost:8000/api/banner', {
        method: 'POST',
        body: bannerData,
      })
        .then(data => {
          if (data.ok) {
            navigate('/admin/banner');
          } else {
            setErrorMessage('Có lỗi xảy ra khi thêm banner');
          }
        })
        .catch(error => {
          setErrorMessage('Lỗi khi thêm banner');
          console.log(error)
        });
  };

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Thêm banner</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Thêm banner</li>
              </ol>
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
                <Link className="btn btn-sm btn-info" to="/admin/banner">
                  <i className="fa fa-arrow-left"></i> Về danh sách
                </Link>
              </div>
            </div>
          </div>
          <div className="card-body">
            {errorMessage && <p className="text-danger">{errorMessage}</p>}
            <form onSubmit={handleSubmit}>
              <div className="row">
                <div className="col-md-9">
                  <div className="mb-3">
                    <label htmlFor="name">Tên banner</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" />
                    {errors.name && <p className="text-danger">{errors.name}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="position">Link</label>
                    <input type="text" name="link" value={formData.link} onChange={handleChange} className="form-control" />
                    {errors.link && <p className="text-danger">{errors.link}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="position">Vị trí</label>
                    <input type="text" name="position" value={formData.position} onChange={handleChange} className="form-control" />
                    {errors.position && <p className="text-danger">{errors.position}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="sort_order">Sắp xếp</label>
                    <input type="number" name="sort_order" value={formData.sort_order} onChange={handleChange} className="form-control" />
                    {errors.sort_order && <p className="text-danger">{errors.sort_order}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="description">Mô tả</label>
                    <textarea name="description" value={formData.description} onChange={handleChange} className="form-control"></textarea>
                    {errors.description && <p className="text-danger">{errors.description}</p>}
                  </div>
                </div>
                <div className="col-md-3">
                  <div className="mb-3">
                    <label htmlFor="image">Hình</label>
                    <input type="file" name="image" onChange={handleChange} className="form-control" />
                  </div>
                  <div className="mb-3">
                    <label htmlFor="status">Trạng thái</label>
                    <select name="status" value={formData.status} onChange={handleChange} className="form-control">
                      <option value="2">Chưa xuất bản</option>
                      <option value="1">Xuất bản</option>
                    </select>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
  );
}

export default AddBanner;
