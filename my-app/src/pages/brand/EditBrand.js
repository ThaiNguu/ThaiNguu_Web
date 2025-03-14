import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';

function EditBrand() {
  const { id } = useParams(); // Lấy ID từ URL
  const [formData, setFormData] = useState({
    name: '',
    sort_order: '',
    description: '',
    status: '2',
    image: null,
  });
  const [errors, setErrors] = useState({});
  const [errorMessage, setErrorMessage] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    // Fetch dữ liệu thương hiệu dựa trên ID
    fetch(`http://localhost:8000/api/brand/${id}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      console.log(data); // Kiểm tra dữ liệu trả về
      setFormData({
        name: data.name || '', // Gán giá trị hiện có
        sort_order: data.sort_order || '', // Gán giá trị hiện có
        description: data.description || '', // Gán giá trị hiện có
        status: data.status || '2', // Gán giá trị hiện có
        image: null, // Không gán hình ảnh từ API
      });
    })
    .catch(error => {
      setErrorMessage('Lỗi khi lấy dữ liệu thương hiệu: ' + error.message);
      console.log(error);
    });
}, [id]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    if (name === 'image') {
      setFormData({ ...formData, [name]: files[0] }); // Lưu trữ tệp hình ảnh
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const validateForm = () => {
    let formErrors = {};
    if (!formData.name) formErrors.name = 'Tên thương hiệu là bắt buộc';
    if (!formData.sort_order) formErrors.sort_order = 'Sắp xếp là bắt buộc';
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

    // Gửi yêu cầu PUT để cập nhật thương hiệu bằng JSON
    const data = {
      name: formData.name,
      sort_order: formData.sort_order,
      description: formData.description,
      status: formData.status,
    };

    updateBrand(data);
  };

  const updateBrand = (data) => {
    fetch(`http://localhost:8000/api/brand/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then(response => {
        if (response.ok) {
          // Nếu có hình ảnh mới, gửi yêu cầu tải hình ảnh lên
          if (formData.image) {
            uploadImage(formData.image);
          } else {
            navigate('/admin/brand'); // Chuyển hướng về danh sách thương hiệu nếu không có hình ảnh
          }
        } else {
          setErrorMessage('Có lỗi xảy ra khi cập nhật thương hiệu');
        }
      })
      .catch(error => {
        setErrorMessage('Lỗi khi cập nhật thương hiệu');
        console.log(error);
      });
  };

  const uploadImage = (imageFile) => {
    const formData = new FormData();
    formData.append('image', imageFile);

    fetch(`http://localhost:8000/api/brand/${id}/upload-image`, {
      method: 'POST',
      body: formData,
    })
      .then(response => {
        if (response.ok) {
          navigate('/admin/brand'); // Chuyển hướng về danh sách thương hiệu
        } else {
          setErrorMessage('Có lỗi xảy ra khi tải hình ảnh');
        }
      })
      .catch(error => {
        setErrorMessage('Lỗi khi tải hình ảnh');
        console.log(error);
      });
  };

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Sửa thương hiệu</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Sửa thương hiệu</li>
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
                <Link className="btn btn-sm btn-info" to="/admin/brand">
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
                    <label htmlFor="name">Tên thương hiệu</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" />
                    {errors.name && <p className="text-danger">{errors.name}</p>}
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
                  <div className="mb-3">
                    <label htmlFor="image">Hình ảnh</label>
                    {formData.image && <img src={URL.createObjectURL(formData.image)} alt="Current" style={{ width: '100px', height: 'auto' }} />}
                    <input type="file" name="image" onChange={handleChange} className="form-control" />
                  </div>
                </div>
                <div className="col-md-3">
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

export default EditBrand;
