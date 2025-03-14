import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';

function ShowBanner() {
  const { id } = useParams(); // Lấy ID từ URL
  const [banner, setBanner] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Gọi API lấy dữ liệu banner
    const fetchBanner = async () => {
      try {
        const response = await fetch(`http://localhost:8000/api/banner/${id}`);
        const data = await response.json();
        if (data.id) {
          setBanner(data);
        } else {
          setError('Banner không tồn tại');
        }
        setLoading(false);
      } catch (err) {
        setError('Lỗi khi tải dữ liệu banner');
        setLoading(false);
      }
    };

    fetchBanner();
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
              {/* Hình ảnh banner */}
              <div className="col-md-6 d-flex align-items-center">
                <img 
                  src={`http://localhost:8000/images/banners/${banner.image}`} 
                  alt={banner.name} 
                  className="img-fluid rounded shadow-sm"
                  style={{ maxHeight: '100%', maxWidth: '100%', objectFit: 'cover' }}
                  loading="lazy"
                />
              </div>

              {/* Thông tin chi tiết banner */}
              <div className="col-md-6">
                <h1 className="display-6 mb-3">{banner.name}</h1>
                <h5 className="text-muted">Link: <a href={banner.link}>{banner.link}</a></h5>
                <h6 className="text-secondary">Vị trí: {banner.position}</h6>
                <p className="mt-3"><strong>Mô tả: </strong>{banner.description}</p>
                <p><strong>Ngày tạo: </strong>{new Date(banner.created_at).toLocaleDateString()}</p>
                <p><strong>Ngày cập nhật: </strong>{new Date(banner.updated_at).toLocaleDateString()}</p>
                <p>
                  <strong>Trạng thái: </strong>
                  {banner.status === 1 ? (
                    <span className="badge bg-success">Hoạt động</span>
                  ) : (
                    <span className="badge bg-secondary">Không hoạt động</span>
                  )}
                </p>

                {/* Nút quay lại */}
                <Link to="/admin/banner" className="btn btn-outline-primary mt-3">Quay lại danh sách</Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ShowBanner;

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
