import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';

function AddProduct() {
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
    // Fetch categories and brands
    Promise.all([
      fetch('http://localhost:8000/api/category').then(response => response.json()),
      fetch('http://localhost:8000/api/brand').then(response => response.json())
    ]).then(([categoryData, brandData]) => {
      setCategories(categoryData.data);
      setBrands(brandData.data);
    }).catch(error => setErrorMessage('Lỗi khi lấy dữ liệu danh mục hoặc thương hiệu'));
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

    const productData = new FormData();
    for (let key in formData) {
      productData.append(key, formData[key]);
    }
    console.log("aaaaaaaa",productData)
    console.log("aaaaaaaa",formData)
    for (let [key, value] of productData.entries()) {
        console.log(`${key}:`, value);
    }
    fetch('http://localhost:8000/api/product', {
        method: 'POST',
        body: productData,
      })
        // .then(response => response.json())
        .then(data => {
          console.log(data); // Kiểm tra phản hồi ở đây
          if (data.ok) {
            navigate('/admin/product');
          } else {
            setErrorMessage('Có lỗi xảy ra khi thêm sản phẩm');
          }
        })
        .catch(error => {
          setErrorMessage('Lỗi khi thêm sản phẩm',error);
          console.log(error)
        });
      
  };

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Thêm sản phẩm</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Thêm sản phẩm</li>
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

export default AddProduct;
