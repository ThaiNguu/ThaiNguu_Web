import React, { useEffect, useState } from "react";
import axios from "axios";
import MainMenu from "../components/main-menu";
import Footer from "../components/Footer";

const OrderDetail = () => {
  const [orders, setOrders] = useState([]);
  const [expandedOrderId, setExpandedOrderId] = useState(null);
  const [orderDetails, setOrderDetails] = useState([]);
  const [loadingDetails, setLoadingDetails] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 5;
  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    if (user) {
      const fetchOrders = async () => {
        try {
          const response = await axios.get("http://localhost:8000/api/order");
          // Filter and sort orders by created_at in descending order
          const userOrders = response.data.data
            .filter((order) => order.user_id === user.id)
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
          setOrders(userOrders);
        } catch (error) {
          console.error("Error fetching orders:", error);
        }
      };

      fetchOrders();
    }
  }, [user]);

  const fetchOrderDetails = async (orderId) => {
    try {
      setLoadingDetails(true);
      const response = await axios.get(`http://localhost:8000/api/orderdetail`);
      const orderDetailsData = response.data.data.filter(
        (detail) => detail.order_id === orderId
      );
      setOrderDetails(orderDetailsData);
      setLoadingDetails(false);
    } catch (error) {
      console.error("Error fetching order details:", error);
      setLoadingDetails(false);
    }
  };

  const toggleAccordion = (orderId) => {
    if (expandedOrderId === orderId) {
      setExpandedOrderId(null);
    } else {
      fetchOrderDetails(orderId);
      setExpandedOrderId(orderId);
    }
  };

  // Pagination logic
  const indexOfLastOrder = currentPage * itemsPerPage;
  const indexOfFirstOrder = indexOfLastOrder - itemsPerPage;
  const currentOrders = orders.slice(indexOfFirstOrder, indexOfLastOrder);
  const totalPages = Math.ceil(orders.length / itemsPerPage);

  return (
    <>
      <MainMenu />
      <div className="container">
        <h1>Lịch sử Đơn Hàng</h1>
        {orders.length > 0 ? (
          <div className="order-list">
            {currentOrders.map((order) => (
              <div key={order.id} className="order-item-unique">
                <div
                  className="order-summary-unique"
                  onClick={() => toggleAccordion(order.id)}
                >
                  <div className="order-summary-content-unique">
                    <p>
                      <strong>ID:</strong> {order.id}
                    </p>
                    <p>
                      <strong>Tên Người Giao:</strong> {order.delivery_name}
                    </p>
                    <p>
                      <strong>Phương thức thanh toán:</strong>
                      {order.type === "paypal"
                        ? "PayPal"
                        : "Thanh toán khi nhận hàng"}
                    </p>
                    <p>
                      <strong>Trạng Thái:</strong>{" "}
                      {order.status === 1 ? "Đã thanh toán" : "Hoàn thành"}
                    </p>
                    <p>
                      <strong>Thời gian đặt hàng:</strong> {order.created_at}
                    </p>
                  </div>
                </div>
                {expandedOrderId === order.id && (
                  <div className="order-details-unique">
                    {loadingDetails ? (
                      <p>Đang tải chi tiết đơn hàng...</p>
                    ) : orderDetails.length > 0 ? (
                      orderDetails.map((detail) => (
                        <div
                          key={detail.id}
                          className="order-detail-item-unique"
                        >
                          <p>
                            <strong>ID Sản Phẩm:</strong> {detail.product_id}
                          </p>
                          <p>
                            <strong>Giá:</strong>{" "}
                            {detail.price.toLocaleString()} VNĐ
                          </p>
                          <p>
                            <strong>Số Lượng:</strong> {detail.qty}
                          </p>
                          <p>
                            <strong>Giảm Giá:</strong> {detail.discount}
                          </p>
                          <p>
                            <strong>Tổng Cộng:</strong>{" "}
                            {detail.amount.toLocaleString()} VNĐ
                          </p>
                        </div>
                      ))
                    ) : (
                      <p>Không có chi tiết cho đơn hàng này.</p>
                    )}
                  </div>
                )}
              </div>
            ))}
          </div>
        ) : (
          <p>Bạn chưa có đơn hàng nào.</p>
        )}

        {/* Pagination controls */}
        <div className="pagination-unique">
          <button
            onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
            disabled={currentPage === 1}
          >
            Trang Trước
          </button>
          <span>
            {currentPage} / {totalPages}
          </span>
          <button
            onClick={() =>
              setCurrentPage((prev) => Math.min(prev + 1, totalPages))
            }
            disabled={currentPage === totalPages}
          >
            Trang Sau
          </button>
        </div>
      </div>
      <Footer />

      <style jsx>{`
        .order-list {
          margin-top: 20px;
        }
        .order-item-unique {
          border: 1px solid #ccc;
          border-radius: 10px;
          margin-bottom: 20px;
          padding: 15px;
          background-color: #f9f9f9;
          transition: box-shadow 0.3s ease;
        }
        .order-item-unique:hover {
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .order-summary-unique {
          cursor: pointer;
          padding: 10px;
          background-color: #e6e6e6;
          border-radius: 10px;
          transition: background-color 0.3s ease;
        }
        .order-summary-unique:hover {
          background-color: #d9d9d9;
        }
        .order-summary-content-unique {
          display: flex;
          flex-wrap: wrap;
          gap: 20px; /* Space between items */
        }
        .order-details-unique {
          margin-top: 10px;
          padding: 15px;
          background-color: #f1f1f1;
          border-radius: 10px;
          transition: max-height 0.4s ease;
        }
        .order-detail-item-unique {
          padding: 8px 0;
          border-bottom: 1px solid #ccc;
        }
        .order-detail-item-unique:last-child {
          border-bottom: none;
        }
        .pagination-unique {
          margin-top: 20px;
          display: flex;
          justify-content: center;
          align-items: center;
        }
        .pagination-unique button {
          margin: 0 10px;
          padding: 5px 15px;
          border: none;
          border-radius: 8px;
          background-color: #28a745; /* Use green instead of blue */
          color: white;
          cursor: pointer;
          transition: background-color 0.3s ease;
        }
        .pagination-unique button:hover {
          background-color: #218838; /* Darker green on hover */
        }
        .pagination-unique button:disabled {
          background-color: #ccc;
          cursor: not-allowed;
        }
      `}</style>
    </>
  );
};

export default OrderDetail;
