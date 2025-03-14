import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';

function EditCategory() {
  const { id } = useParams(); // Lấy ID từ URL
  const [categories, setCategories] = useState([]); // Danh sách danh mục cha
  const [formData, setFormData] = useState({
    name: '',
    parent_id: '',
    sort_order: '',
    description: '',
    status: '2', // Trạng thái mặc định là chưa xuất bản
    image: null,
  });
  const [errors, setErrors] = useState({});
  const [errorMessage, setErrorMessage] = useState(null);
  const navigate = useNavigate();

  // Lấy dữ liệu danh mục cần chỉnh sửa
  useEffect(() => {
    fetch(`http://localhost:8000/api/category/${id}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Lỗi khi lấy dữ liệu danh mục');
        }
        return response.json();
      })
      .then(data => {
        setFormData({
          name: data.name || '',
          parent_id: data.parent_id || '',
          sort_order: data.sort_order || '',
          description: data.description || '',
          status: data.status || '2', // Gán trạng thái từ dữ liệu
          image: null,
        });
      })
      .catch(error => setErrorMessage(error.message));
  }, [id]);

  // Lấy danh sách các danh mục cha
  useEffect(() => {
    fetch('http://localhost:8000/api/category')
      .then(response => response.json())
      .then(data => {
        if (data && Array.isArray(data.data)) {
          setCategories(data.data); // Nếu API trả về danh sách danh mục
        } else {
          setCategories([]); // Nếu không có danh mục nào, để rỗng
        }
      })
      .catch(error => setErrorMessage('Lỗi khi lấy dữ liệu danh mục'));
  }, []);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    if (name === 'image') {
      setFormData({ ...formData, image: files[0] }); // Lưu trữ hình ảnh
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

    // Chuẩn bị dữ liệu danh mục dưới dạng JSON (không bao gồm ảnh)
    const data = {
      name: formData.name,
      parent_id: formData.parent_id || null, // Gửi null nếu không có danh mục cha
      sort_order: formData.sort_order,
      description: formData.description,
      status: formData.status,
    };

    updateCategory(data);
  };
  const updateCategory = (data) => {
    fetch(`http://localhost:8000/api/category/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then(response => {
        if (response.ok) {
          if (formData.image) {
            uploadImage(); 
          } else {
            navigate('/admin/category');
          }
        } else {
          throw new Error('Có lỗi xảy ra khi cập nhật danh mục');
        }
      })
      .catch(error => setErrorMessage(error.message));
  };

  const uploadImage = () => {
    const imageData = new FormData();
    imageData.append('image', formData.image);

    fetch(`http://localhost:8000/api/category/${id}/upload-image`, {
      method: 'POST',
      body: imageData,
    })
      .then(response => {
        if (response.ok) {
          navigate('/admin/category');
        } else {
          throw new Error('Có lỗi xảy ra khi tải lên hình ảnh');
        }
      })
      .catch(error => setErrorMessage(error.message));
  };

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Sửa danh mục</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Sửa danh mục</li>
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
                  </div>
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
                    <label htmlFor="image">Hình ảnh</label>
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

export default EditCategory;
