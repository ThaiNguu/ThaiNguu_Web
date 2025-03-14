import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import usePagination from '../usePagination';
function BrandList() {
  const [brands, setBrands] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
 
  useEffect(() => {
    fetch('http://localhost:8000/api/brand')
      .then(response => response.json())
      .then(data => {
        if (Array.isArray(data.data)) {
          const filteredBrands = data.data.filter(brand => brand.status !== 0);
          const sortedBrands = filteredBrands.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
          setBrands(sortedBrands);
        } else {
          setError('Dữ liệu trả về không phải là mảng');
        }
        setLoading(false);
      })
      .catch(error => {
        setError('Lỗi khi lấy dữ liệu thương hiệu');
        setLoading(false);
      });
  }, []);

  const handleStatusChange = async (id) => {
    try {
      const response = await fetch(`http://localhost:8000/api/brand/status/${id}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      });
  
      const data = await response.json();
  
      if (data.message === 'Brand status updated successfully') {
        // Cập nhật trạng thái của thương hiệu trong state
        setBrands(prevBrands =>
          prevBrands.map(brand =>
            brand.id === id ? { ...brand, status: brand.status === 1 ? 2 : 1 } : brand
          )
        );
      } else {
        setError('Lỗi khi thay đổi trạng thái thương hiệu');
      }
    } catch (error) {
      console.error('Error when changing brand status:', error);
      setError('Lỗi khi thay đổi trạng thái');
    }
  };
  const handleDelete = async (id) => {
    try {
        // Gửi yêu cầu PATCH để cập nhật trạng thái của banner thành 0
        const response = await fetch(`http://localhost:8000/api/brand/delete/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (data.message === 'Brand status updated successfully') {
            // Cập nhật trạng thái của brand trong state
            setBrands(prevBrands => prevBrands.filter(brand => brand.id !== id));
        } else {
            setError('Lỗi khi xóa brand');
        }
    } catch (error) {
        console.error('Error when deleting brand:', error);
        setError('Lỗi khi xóa brand');
    }
};
const itemsPerPage = 5;
  const { currentData, currentPage, totalPages, nextPage, prevPage, goToPage } = usePagination(brands, itemsPerPage);
  if (loading) return <p>Loading...</p>;
  if (error) return <p>{error}</p>;

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Quản lý thương hiệu</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item"><Link to="/admin">Home</Link></li>
                <li className="breadcrumb-item active">Quản lý thương hiệu</li>
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
                <Link to="/admin/brand/new" className="btn btn-sm btn-success">
                  <i className="fas fa-plus"></i> Thêm thương hiệu
                </Link>
                <Link className="btn btn-sm btn-danger" to="/admin/brand/trash">
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
                  <th className="text-center" style={{ width: '300px' }}>Tên thương hiệu</th>
                  <th className="text-center" style={{ width: '200px' }}>Tên slug</th>
                  <th className="text-center" style={{ width: '250px' }}>Chức năng</th>
                </tr>
              </thead>
              <tbody>
                {currentData().map(brand => (
                  <tr key={brand.id}>
                    <td><input type="checkbox" /></td>
                    <td>{brand.id}</td>
                    <td className="text-center">
                      <img src={`http://localhost:8000/images/brands/${brand.image}`} alt={brand.name} style={{ width: '100px' }} />
                    </td>
                    <td>{brand.name}</td>
                    <td>{brand.slug}</td>
                    
                    <td className="text-center">
                      <button onClick={() => handleStatusChange(brand.id)} className={`btn ${brand.status === 1 ? 'btn-success' : 'btn-danger'}`} style={{ width: '40px' }}>
                        <i className={`fas fa-toggle-${brand.status === 1 ? 'on' : 'off'}`}></i>
                      </button>
                      <Link to={`/admin/brand/edit/${brand.id}`} className="btn btn-primary" style={{ width: '40px' }}>
                        <i className="fas fa-edit"></i>
                      </Link>
                      <Link to={`/admin/brand/show/${brand.id}`} className="btn btn-info" style={{ width: '40px' }}>
                        <i className="fas fa-eye"></i>
                      </Link>
                      <button onClick={() => handleDelete(brand.id)} className="btn btn-danger" style={{ width: '40px' }}>
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

export default BrandList;
