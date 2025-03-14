import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import usePagination from "../usePagination";
import "../../css/pagination.css";
function ProductList() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch("http://localhost:8000/api/product")
      .then((response) => response.json())
      .then((data) => {
        if (Array.isArray(data.data)) {
          const filteredProducts = data.data.filter(
            (product) => product.status !== 0
          );
          const sortedProducts = filteredProducts.sort(
            (a, b) => new Date(b.created_at) - new Date(a.created_at)
          );
          setProducts(sortedProducts);
        } else {
          setError("Dữ liệu trả về không phải là mảng");
        }
        setLoading(false);
      })
      .catch((error) => {
        setError("Lỗi khi lấy dữ liệu sản phẩm");
        setLoading(false);
      });
  }, []);

  const handleStatusChange = async (id) => {
    try {
      const response = await fetch(
        `http://localhost:8000/api/product/status/${id}`,
        {
          method: "PATCH",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        }
      );

      const data = await response.json();

      if (data.message === "Product status updated successfully") {
        setProducts((prevProducts) =>
          prevProducts.map((product) =>
            product.id === id
              ? { ...product, status: product.status === 1 ? 2 : 1 }
              : product
          )
        );
      } else {
        setError("Lỗi khi thay đổi trạng thái sản phẩm");
      }
    } catch (error) {
      console.error("Error when changing product status:", error);
      setError("Lỗi khi thay đổi trạng thái sản phẩm");
    }
  };
  const handleDelete = async (id) => {
    try {
      // Gửi yêu cầu PATCH để cập nhật trạng thái của banner thành 0
      const response = await fetch(
        `http://localhost:8000/api/product/delete/${id}`,
        {
          method: "PATCH",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        }
      );

      const data = await response.json();

      if (data.message === "Product status updated successfully") {
        setProducts((prevProducts) =>
          prevProducts.filter((product) => product.id !== id)
        );
      } else {
        setError("Lỗi khi xóa product");
      }
    } catch (error) {
      console.error("Error when deleting product:", error);
      setError("Lỗi khi xóa product");
    }
  };
  const itemsPerPage = 5;
  const { currentData, currentPage, totalPages, nextPage, prevPage, goToPage } =
    usePagination(products, itemsPerPage);
  if (loading) return <p>Loading...</p>;
  if (error) return <p>{error}</p>;

  return (
    <div className="content-wrapper">
      <section className="content-header">
        <div className="container-fluid">
          <div className="row mb-2">
            <div className="col-sm-6">
              <h1>Quản lý sản phẩm</h1>
            </div>
            <div className="col-sm-6">
              <ol className="breadcrumb float-sm-right">
                <li className="breadcrumb-item">
                  <Link to="/admin">Home</Link>
                </li>
                <li className="breadcrumb-item active">Quản lý sản phẩm</li>
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
                <Link
                  to="/admin/product/new"
                  className="btn btn-sm btn-success"
                >
                  <i className="fas fa-plus"></i> Thêm sản phẩm
                </Link>
                <Link
                  className="btn btn-sm btn-danger"
                  to="/admin/product/trash"
                >
                  <i className="fa fa-trash text-white"></i> Thùng rác
                </Link>
              </div>
            </div>
          </div>
          <div className="card-body">
            <table className="table table-bordered">
              <thead>
                <tr>
                  <th className="text-center" style={{ width: "30px" }}>
                    <input type="checkbox" />
                  </th>
                  <th className="text-center" style={{ width: "30px" }}>
                    ID
                  </th>
                  <th className="text-center" style={{ width: "130px" }}>
                    Hình ảnh
                  </th>
                  <th className="text-center" style={{ width: "300px" }}>
                    Tên sản phẩm
                  </th>
                  <th className="text-center">Tên danh mục</th>
                  <th className="text-center" style={{ width: "200px" }}>
                    Tên thương hiệu
                  </th>
                  <th className="text-center" style={{ width: "250px" }}>
                    Chức năng
                  </th>
                </tr>
              </thead>
              <tbody>
                {currentData().map((product) => (
                  <tr key={product.id}>
                    <td>
                      <input type="checkbox" />
                    </td>
                    <td>{product.id}</td>
                    <td className="text-center">
                      <img
                        src={`http://localhost:8000/images/products/${product.image}`}
                        alt={product.name}
                        style={{ width: "100px" }}
                      />
                    </td>
                    <td>{product.name}</td>
                    <td>{product.categoryname}</td>
                    <td>{product.brandname}</td>
                    <td className="text-center">
                      <button
                        onClick={() => handleStatusChange(product.id)}
                        className={`btn ${
                          product.status === 1 ? "btn-success" : "btn-danger"
                        }`}
                        style={{ width: "40px" }}
                      >
                        <i
                          className={`fas fa-toggle-${
                            product.status === 1 ? "on" : "off"
                          }`}
                        ></i>
                      </button>
                      <Link
                        to={`/admin/product/edit/${product.id}`}
                        className="btn btn-primary"
                        style={{ width: "40px" }}
                      >
                        <i className="fas fa-edit"></i>
                      </Link>
                      <Link
                        to={`/admin/product/show/${product.id}`}
                        className="btn btn-info"
                        style={{ width: "40px" }}
                      >
                        <i className="fas fa-eye"></i>
                      </Link>
                      <button
                        onClick={() => handleDelete(product.id)}
                        className="btn btn-danger"
                        style={{ width: "40px" }}
                      >
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

export default ProductList;
