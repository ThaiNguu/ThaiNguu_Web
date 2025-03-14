import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';

function ShowProduct() {
  const { id } = useParams(); // Lấy ID từ URL
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {

    const fetchProduct = async () => {
      try {
        const response = await fetch(`http://localhost:8000/api/product/${id}`);
        const data = await response.json();
        if (data.id) {
          setProduct(data);
        } else {
          setError('Sản phẩm không tồn tại');
        }
        setLoading(false);
      } catch (err) {
        setError('Lỗi khi tải dữ liệu sản phẩm');
        setLoading(false);
      }
    };

    fetchProduct();
  }, [id]);

  if (loading) {
    return (
      <div className="container-fluid mt-5">
        <div className="row justify-content-center">
          <div className="col-md-10 col-lg-8">
            {/* Custom Loading Placeholder */}
            <div className="loading-placeholder">
              <div className="loading-img" />
              <div className="loading-text" />
              <div className="loading-text short" />
              <div className="loading-text" />
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (error) return <p className="alert alert-danger text-center">{error}</p>;

  return (
    <div className="container-fluid mt-5">
      <div className="row justify-content-center">
        <div className="col-md-10 col-lg-8">
          <div className="card shadow-sm p-4">
            <div className="row">
              {/* Hình ảnh sản phẩm */}
              <div className="col-md-6 d-flex align-items-center">
                <img 
                  src={`http://localhost:8000/images/products/${product.image}`} 
                  alt={product.name} 
                  className="img-fluid rounded shadow-sm"
                  style={{ maxHeight: '100%', maxWidth: '100%', objectFit: 'cover' }}
                  loading="lazy"
                />
              </div>

              {/* Thông tin chi tiết sản phẩm */}
              <div className="col-md-6">
                <h1 className="display-6 mb-3">{product.name}</h1>
                <div dangerouslySetInnerHTML={{ __html: product.detail }} className="mb-3"></div>
                <h4 className="text-success">Giá: {product.price.toLocaleString()} VND</h4>
                {product.pricesale && (
                  <h5 className="text-danger">
                    Giá khuyến mãi: {product.pricesale.toLocaleString()} VND
                  </h5>
                )}
                <p className="mt-3"><strong>Mô tả: </strong>{product.description}</p>
                <p><strong>ID Danh mục: </strong>{product.category_id}</p>
                <p><strong>ID Thương hiệu: </strong>{product.brand_id}</p>
                <p><strong>Ngày tạo: </strong>{new Date(product.created_at).toLocaleDateString()}</p>
                <p><strong>Ngày cập nhật: </strong>{new Date(product.updated_at).toLocaleDateString()}</p>
                <p>
                  <strong>Trạng thái: </strong>
                  {product.status === 1 ? (
                    <span className="badge bg-success">Hoạt động</span>
                  ) : (
                    <span className="badge bg-secondary">Không hoạt động</span>
                  )}
                </p>

                {/* Nút quay lại */}
                <Link to="/admin/product" className="btn btn-outline-primary mt-3">Quay lại danh sách</Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ShowProduct;

/* Custom CSS for loading placeholders */
const styles = `
.loading-placeholder {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.loading-img {
  width: 100%;
  height: 400px;
  background-color: #f0f0f0;
}

.loading-text {
  height: 20px;
  background-color: #f0f0f0;
  border-radius: 4px;
}

.loading-text.short {
  width: 50%;
}
`;

document.head.insertAdjacentHTML("beforeend", `<style>${styles}</style>`);
