import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import usePagination from '../usePagination';
function UserList() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
 
  useEffect(() => {
    fetch('http://localhost:8000/api/users')
      .then(response => response.json())
      .then(data => {
        if (Array.isArray(data.data)) {
          const filteredUsers = data.data.filter(user => user.status !== 0);
          const sortedUsers = filteredUsers.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
          setUsers(sortedUsers);
        } else {
          setError('Dữ liệu trả về không phải là mảng');
        }
        setLoading(false);
      })
      .catch(error => {
        setError('Lỗi khi lấy dữ liệu thành viên');
        setLoading(false);
      });
  }, []);

  const handleStatusChange = async (id) => {
    try {
      const response = await fetch(`http://localhost:8000/api/user/status/${id}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      });
  
      const data = await response.json();
  
      if (data.message === 'User status updated successfully') {
        // Cập nhật trạng thái của danh mục trong state
        setUsers(prevUsers => prevUsers.map(user =>
          user.id === id ? { ...user, status: user.status === 1 ? 2 : 1 } : user
        ));
      } else {
        setError('Lỗi khi thay đổi trạng thái danh mục');
      }
    } catch (error) {
      console.error('Error when changing user status:', error);
      setError('Lỗi khi thay đổi trạng thái danh mục');
    }
  };
  const handleDelete = async (id) => {
    try {
        // Gửi yêu cầu PATCH để cập nhật trạng thái của banner thành 0
        const response = await fetch(`http://localhost:8000/api/user/delete/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (data.message === 'User status updated successfully') {
            // Cập nhật trạng thái của user trong state
            setUsers(prevUsers => prevUsers.filter(user => user.id !== id));
        } else {
            setError('Lỗi khi xóa user');
        }
    } catch (error) {
        console.error('Error when deleting user:', error);
        setError('Lỗi khi xóa user');
    }
};
const itemsPerPage = 5;
  const { currentData, currentPage, totalPages, nextPage, prevPage, goToPage } = usePagination(users, itemsPerPage);
  if (loading) return <p>Loading...</p>;
  if (error) return <p>{error}</p>;

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Quản lý thành viên</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Quản lý thành viên</li>
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
                <Link to="/admin/user/new" className="btn btn-sm btn-success">
                  <i className="fas fa-plus"></i> Thêm thành viên
                </Link>
                <Link className="btn btn-sm btn-danger" to="/admin/user/trash">
                  <i className="fa fa-trash text-light"></i> Thùng rác
                </Link>
              </div>
            </div>
          </div>
          <div className="card-body">
            <table className="table table-bordered">
              <thead>
                <tr>
                  <th className="text-center" style={{ width: '30px' }}><input type="checkbox" /></th>
                  <th className="text-center" style={{ width: '30px' }}>ID</th>
                  <th className="text-center" style={{ width: '130px' }}>Hình ảnh</th>
                  <th className="text-center" style={{ width: '300px' }}>Họ tên</th>
                  <th className="text-center" style={{ width: '200px' }}>Điện thoại</th>
                  <th className="text-center" style={{ width: '200px' }}>Email</th>
                  <th className="text-center" style={{ width: '250px' }}>Chức năng</th>
                </tr>
              </thead>
              <tbody>
                {currentData().map(user => (
                  <tr key={user.id}>
                    <td><input type="checkbox" /></td>
                    <td>{user.id}</td>
                    <td className="text-center">
                      <img src={`http://localhost:8000/images/users/${user.image}`} alt={user.name} style={{ width: '100px' }} />
                    </td>
                    <td>{user.name}</td>
                    <td>{user.phone}</td>
                    <td>{user.email}</td>
                    <td className="text-center">
                      <button onClick={() => handleStatusChange(user.id)} className={`btn ${user.status === 1 ? 'btn-success' : 'btn-danger'}`} style={{ width: '40px' }}>
                        <i className={`fas fa-toggle-${user.status === 1 ? 'on' : 'off'}`}></i>
                      </button>
                      <Link to={`/admin/user/edit/${user.id}`} className="btn btn-primary" style={{ width: '40px' }}>
                        <i className="fas fa-edit"></i>
                      </Link>
                      <Link to={`/admin/user/show/${user.id}`} className="btn btn-info" style={{ width: '40px' }}>
                        <i className="fas fa-eye"></i>
                      </Link>
                      <button onClick={() => handleDelete(user.id)} className="btn btn-danger" style={{ width: '40px' }}>
                        <i className="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            <div className="pagination-controls">
              <button onClick={prevPage} disabled={currentPage === 1}>
                Previous
              </button>
              {Array.from({ length: totalPages }, (_, i) => (
                <button key={i + 1} onClick={() => goToPage(i + 1)}>
                  {i + 1}
                </button>
              ))}
              <button onClick={nextPage} disabled={currentPage === totalPages}>
                Next
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}

export default UserList;
