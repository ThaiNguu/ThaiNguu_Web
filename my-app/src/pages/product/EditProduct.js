import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';

function EditProduct() {
  const { id } = useParams(); // Lấy ID sản phẩm từ URL
  const [categories, setCategories] = useState([]);
  const [brands, setBrands] = useState([]);
  const [formData, setFormData] = useState({
    name: '',
    detail: '',
    description: '',
    category_id: '',
    brand_id: '',
    price: '',
    pricesale: '',
    status: '2',
    image: null,
  });
  const [errors, setErrors] = useState({});
  const [errorMessage, setErrorMessage] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    Promise.all([
      fetch('http://localhost:8000/api/category').then(response => response.json()),
      fetch('http://localhost:8000/api/brand').then(response => response.json()),
      fetch(`http://localhost:8000/api/product/${id}`).then(response => response.json())
    ])
      .then(([categoryData, brandData, productData]) => {
        // Handle successful response
        setCategories(categoryData.data);
        setBrands(brandData.data);
        setFormData({
          name: productData.name,
          detail: productData.detail,
          description: productData.description,
          category_id: productData.category_id,
          brand_id: productData.brand_id,
          price: productData.price,
          pricesale: productData.pricesale,
          status: productData.status,
          image: null,
        });
      })
      .catch(error => {
        // Handle error
        console.error('Error fetching data:', error);
        setErrorMessage('Lỗi khi lấy dữ liệu sản phẩm hoặc danh mục, thương hiệu');
      });
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
    if (!formData.name) formErrors.name = 'Tên sản phẩm là bắt buộc';
    if (!formData.detail) formErrors.detail = 'Chi tiết là bắt buộc';
    if (!formData.category_id) formErrors.category_id = 'Danh mục là bắt buộc';
    if (!formData.brand_id) formErrors.brand_id = 'Thương hiệu là bắt buộc';
    if (!formData.price) formErrors.price = 'Giá là bắt buộc';
    return formErrors;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
  
    const formErrors = validateForm();
    if (Object.keys(formErrors).length > 0) {
      setErrors(formErrors);
      return;
    }
  
    // Chuẩn bị dữ liệu sản phẩm dưới dạng JSON (không bao gồm ảnh)
    const productData = {
      name: formData.name,
      detail: formData.detail,
      description: formData.description,
      category_id: formData.category_id,
      brand_id: formData.brand_id,
      price: formData.price,
      pricesale: formData.pricesale,
      status: formData.status,
    };
  
    // Gửi dữ liệu sản phẩm không bao gồm ảnh
    fetch(`http://localhost:8000/api/product/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(productData),
    })
      .then(response => response.json())
      .then(data => {
        if (data) {
          // Nếu có ảnh, upload ảnh riêng
          if (formData.image) {
            const imageData = new FormData();
            imageData.append('image', formData.image);
  
            fetch(`http://localhost:8000/api/product/${id}/upload-image`, {
              method: 'POST',
              body: imageData,
            })
              .then(response => response.json())
              .then(imgData => {
                if (imgData) {
                  navigate('/admin/product');
                } else {
                  setErrorMessage('Có lỗi xảy ra khi cập nhật ảnh');
                }
              })
              .catch(error => {
                console.error('Lỗi khi upload ảnh:', error);
                setErrorMessage('Lỗi khi upload ảnh');
              });
          } else {
            navigate('/admin/product');
          }
        } else {
          setErrorMessage('Có lỗi xảy ra khi cập nhật sản phẩm');
        }
      })
      .catch(error => {
        console.error('Lỗi:', error);
        setErrorMessage('Lỗi khi cập nhật sản phẩm');
      });
  };
  

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Chỉnh sửa sản phẩm</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Chỉnh sửa sản phẩm</li>
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
                <Link className="btn btn-sm btn-info" to="/admin/product">
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
                    <label htmlFor="name">Tên sản phẩm</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} className="form-control" />
                    {errors.name && <p className="text-danger">{errors.name}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="detail">Chi tiết</label>
                    <textarea name="detail" value={formData.detail} onChange={handleChange} rows="8" className="form-control"></textarea>
                    {errors.detail && <p className="text-danger">{errors.detail}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="description">Mô tả</label>
                    <textarea name="description" value={formData.description} onChange={handleChange} className="form-control"></textarea>
                  </div>
                </div>
                <div className="col-md-3">
                  <div className="mb-3">
                    <label htmlFor="category_id">Danh mục</label>
                    <select name="category_id" value={formData.category_id} onChange={handleChange} className="form-control">
                      <option value="">Chọn danh mục</option>
                      {categories.map(category => (
                        <option key={category.id} value={category.id}>{category.name}</option>
                      ))}
                    </select>
                    {errors.category_id && <p className="text-danger">{errors.category_id}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="brand_id">Thương hiệu</label>
                    <select name="brand_id" value={formData.brand_id} onChange={handleChange} className="form-control">
                      <option value="">Chọn thương hiệu</option>
                      {brands.map(brand => (
                        <option key={brand.id} value={brand.id}>{brand.name}</option>
                      ))}
                    </select>
                    {errors.brand_id && <p className="text-danger">{errors.brand_id}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="price">Giá</label>
                    <input type="number" name="price" value={formData.price} onChange={handleChange} className="form-control" />
                    {errors.price && <p className="text-danger">{errors.price}</p>}
                  </div>
                  <div className="mb-3">
                    <label htmlFor="pricesale">Giá khuyến mãi</label>
                    <input type="number" name="pricesale" value={formData.pricesale} onChange={handleChange} className="form-control" />
                  </div>
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

export default EditProduct;
