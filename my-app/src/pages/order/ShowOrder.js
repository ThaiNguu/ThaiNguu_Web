import React, { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import axios from "axios";

const ShowOrder = () => {
  const { id } = useParams(); // Lấy id từ URL
  const [order, setOrder] = useState(null);
  const [orderDetails, setOrderDetails] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchOrder = async () => {
      try {
        const response = await axios.get(`http://localhost:8000/api/order/${id}`);
        console.log("Order data:", response.data); // Thêm dòng này để kiểm tra
        setOrder(response.data);
      } catch (error) {
        setError("Error fetching order");
      } finally {
        setLoading(false);
      }
    };
    
    

    const fetchOrderDetails = async () => {
      try {
        const response = await axios.get(`http://localhost:8000/api/orderdetail`);
        const details = response.data.data.filter(
          (detail) => detail.order_id === Number(id) // So sánh với id
        );
        setOrderDetails(details);
      } catch (error) {
        setError("Error fetching order details");
      }
    };

    if (id) {
      fetchOrder();
      fetchOrderDetails();
    }
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

  if (error) {
    return <p className="alert alert-danger text-center">{error}</p>;
  }

  return (
    <div className="container-fluid mt-5">
      <div className="row justify-content-center">
        <div className="col-md-10 col-lg-8">
          <div className="card shadow-sm p-4">
            <div className="row">
              {/* Thông tin đơn hàng */}
              <div className="col-md-12">
                <h1 className="display-6 mb-3">Chi Tiết Đơn Hàng</h1>
                {order ? (
                  <>
                    <h2>Thông Tin Đơn Hàng</h2>
                    <p><strong>ID:</strong> {order.id}</p>
                    <p><strong>Tên Người Giao:</strong> {order.delivery_name}</p>
                    <p><strong>Điện Thoại:</strong> {order.delivery_phone}</p>
                    <p><strong>Địa Chỉ:</strong> {order.delivery_address}</p>
                    <p><strong>Ghi Chú:</strong> {order.note}</p>
                    <p><strong>Ngày Tạo:</strong> {new Date(order.created_at).toLocaleDateString()}</p>
                    <p><strong>Trạng Thái:</strong> {order.status === 1 ? "Đã hoàn thành" : "Chưa hoàn thành"}</p>
                    <p><strong>Loại:</strong> {order.type}</p>
                  </>
                ) : (
                  <p>Không có thông tin đơn hàng.</p>
                )}
              </div>

              {/* Chi tiết đơn hàng */}
              <div className="col-md-12 mt-4">
                <h2>Chi Tiết Đơn Hàng</h2>
                {orderDetails.length > 0 ? (
                  <table className="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Giảm Giá</th>
                        <th>Tổng Cộng</th>
                      </tr>
                    </thead>
                    <tbody>
                      {orderDetails.map((detail) => (
                        <tr key={detail.id}>
                          <td>{detail.product_id}</td>
                          <td>{detail.price}</td>
                          <td>{detail.qty}</td>
                          <td>{detail.discount}</td>
                          <td>{detail.amount}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                ) : (
                  <p>Không có chi tiết nào cho đơn hàng này.</p>
                )}
              </div>
            </div>

            {/* Nút quay lại */}
            <div className="text-end mt-3">
              <Link to="/admin/order" className="btn btn-outline-primary">Quay lại danh sách</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ShowOrder;

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
