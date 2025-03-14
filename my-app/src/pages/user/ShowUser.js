import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';

function ShowUser() {
  const { id } = useParams(); // Lấy ID từ URL
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Gọi API để lấy dữ liệu người dùng
    const fetchUser = async () => {
      try {
        const response = await fetch(`http://localhost:8000/api/user/${id}`);
        const data = await response.json();
        if (data.id) {
          setUser(data);
        } else {
          setError('Người dùng không tồn tại');
        }
        setLoading(false);
      } catch (err) {
        setError('Lỗi khi tải dữ liệu người dùng');
        setLoading(false);
      }
    };

    fetchUser();
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
              {/* Thông tin người dùng */}
              <div className="col-md-4">
                <img
                  src={`http://localhost:8000/images/users/${user.image}`} // Đường dẫn đến ảnh
                  alt={user.name}
                  className="img-fluid rounded-circle mb-3"
                />
              </div>
              <div className="col-md-8">
                <h1 className="display-6 mb-3">{user.name}</h1>
                <p><strong>Tên đăng nhập: </strong>{user.username}</p>
                <p><strong>Email: </strong>{user.email}</p>
                <p><strong>Điện thoại: </strong>{user.phone}</p>
                <p><strong>Giới tính: </strong>{user.gender === "1" ? "Nam" : "Nữ"}</p>
                <p><strong>Địa chỉ: </strong>{user.address}</p>
                <p><strong>Vai trò: </strong>{user.roles}</p>
                <p><strong>Ngày tạo: </strong>{user.created_at ? new Date(user.created_at).toLocaleDateString() : "Chưa xác định"}</p>
                <p><strong>Ngày cập nhật: </strong>{new Date(user.updated_at).toLocaleDateString()}</p>
                <p>
                  <strong>Trạng thái: </strong>
                  {user.status === 1 ? (
                    <span className="badge bg-success">Đang hoạt động</span>
                  ) : (
                    <span className="badge bg-secondary">Không hoạt động</span>
                  )}
                </p>
                
                {/* Nút quay lại */}
                <Link to="/admin/user" className="btn btn-outline-primary mt-3">Quay lại danh sách</Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ShowUser;

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
