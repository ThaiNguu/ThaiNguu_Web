import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';

function AddCategory() {
  const [categories, setCategories] = useState([]);
  const [formData, setFormData] = useState({
    name: '',
    parent_id: '',
    sort_order: '', 
    description: '',
    status: '2',
    image: null,
  });
  const [errors, setErrors] = useState({});
  const [errorMessage, setErrorMessage] = useState(null);
  const navigate = useNavigate();

  // Lấy danh sách danh mục hiện có
  useEffect(() => {
    fetch('http://localhost:8000/api/category')
      .then(response => response.json())
      .then(data => setCategories(data.data))
      .catch(error => setErrorMessage('Lỗi khi lấy dữ liệu danh mục'));
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
    if (!formData.name) formErrors.name = 'Tên danh mục là bắt buộc';
    if (!formData.description) formErrors.description = 'Mô tả là bắt buộc';
    
    return formErrors;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const formErrors = validateForm();
    if (Object.keys(formErrors).length > 0) {
      setErrors(formErrors);
      return;
    }

    const categoryData = new FormData();
    for (let key in formData) {
      categoryData.append(key, formData[key]);
    }

    fetch('http://localhost:8000/api/category', {
      method: 'POST',
      body: categoryData,
    })
      .then(data => {
        if (data.ok) {
          navigate('/admin/category');
        } else {
          setErrorMessage('Có lỗi xảy ra khi thêm danh mục');
        }
      })
      .catch(error => {
        setErrorMessage('Lỗi khi thêm danh mục');
        console.log(error);
      });
  };

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Thêm danh mục</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Thêm danh mục</li>
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
                <Link className="btn btn-sm btn-info" to="/admin/category">
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
                    <label htmlFor="name">Tên danh mục</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" />
                    {errors.name && <p className="text-danger">{errors.name}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="description">Mô tả</label>
                    <textarea name="description" value={formData.description} onChange={handleChange} className="form-control"></textarea>
                    {errors.description && <p className="text-danger">{errors.description}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="sort_order">Sắp xếp</label>
                    <input type="number" name="sort_order" value={formData.sort_order} onChange={handleChange} className="form-control" />
                    {errors.sort_order && <p className="text-danger">{errors.sort_order}</p>}
                  </div>
                  {/* Dropdown cho danh mục cha */}
                  <div className="mb-3">
                    <label htmlFor="parent_id">Danh mục cha</label>
                    <select name="parent_id" value={formData.parent_id} onChange={handleChange} className="form-control">
                      <option value="">Chọn danh mục cha</option>
                      {categories.map(category => (
                        <option key={category.id} value={category.id}>
                          {category.name}
                        </option>
                      ))}
                    </select>
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

export default AddCategory;
