import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import MainMenu from "../components/main-menu";
import Footer from "../components/Footer";
import "../css/CheckoutPage.css";
import { PayPalButton } from "react-paypal-button-v2";

const CheckoutPage = () => {
  const [cartItems, setCartItems] = useState([]);
  const [user, setUser] = useState(null);
  const [formData, setFormData] = useState({
    delivery_name: "",
    delivery_email: "",
    delivery_phone: "",
    delivery_address: "",
    note: "",
    delivery_gender: "male",
  });
  const [paymentMethod, setPaymentMethod] = useState("cash");
  const [isSuccess, setIsSuccess] = useState(false);
  const [errorMessage, setErrorMessage] = useState("");
  const navigate = useNavigate();
  const exchangeRate = 24000;

  useEffect(() => {
    const items = JSON.parse(localStorage.getItem("cart")) || [];
    const currentUser = JSON.parse(localStorage.getItem("user"));

    setCartItems(items);
    setUser(currentUser);

    if (currentUser) {
      setFormData({
        ...formData,
        delivery_name: currentUser.name,
        delivery_email: currentUser.email,
        delivery_phone: currentUser.phone,
        delivery_address: currentUser.address,
      });
    }
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const orderAmountInVND = cartItems.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );

    const orderData = {
      user_id: user?.id || null,
      delivery_name: formData.delivery_name || "",
      delivery_email: formData.delivery_email || "",
      delivery_phone: formData.delivery_phone || "",
      delivery_address: formData.delivery_address || "",
      delivery_gender: formData.delivery_gender || "",
      note: formData.note || "",
      created_at: new Date().toISOString(),
      type: "cash",
      total_amount_vnd: orderAmountInVND,
      products: cartItems.map((item) => ({
        product_id: item.id,
        price: item.price,  // giá đã ở đơn vị VND
        qty: item.quantity,
        discount: 0,
        amount: item.price * item.quantity,  // tổng giá trị của sản phẩm
      })),
    };

    try {
      const response = await axios.post(
        "http://localhost:8000/api/payment",
        orderData
      );
      console.log("Đơn hàng đã được gửi thành công", response.data);
      localStorage.removeItem("cart");
      setIsSuccess(true);
      setErrorMessage("");
    } catch (error) {
      console.error("Lỗi khi gửi đơn hàng", error);
      setErrorMessage("Có lỗi xảy ra khi gửi đơn hàng. Vui lòng thử lại.");
    }
  };


  const handlePayPalSuccess = async (details, data) => {
    const orderAmountInVND = cartItems.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );
  
    const orderAmountInUSD = (orderAmountInVND / exchangeRate).toFixed(2);
  
    const orderData = {
      user_id: user?.id || null,
      delivery_name: formData.delivery_name || "",
      delivery_email: formData.delivery_email || "",
      delivery_phone: formData.delivery_phone || "",
      delivery_address: formData.delivery_address || "",
      delivery_gender: formData.delivery_gender || "",
      note: formData.note || "",
      created_at: new Date().toISOString(),
      type: "paypal",
      paymentId: data.paymentID,
      total_amount_vnd: orderAmountInVND,
      total_amount_usd: orderAmountInUSD,
      products: cartItems.map((item) => ({
        product_id: item.id,
        price: item.price,
        qty: item.quantity,
        discount: 0,
        amount: item.price * item.quantity,
      })),
    };
  
    try {
      const response = await axios.post(
        "http://localhost:8000/api/payment",
        orderData
      );
      const sound = new Audio("/sound/success-sound.mp3"); // Ensure this path is correct
      sound.play();
      console.log("Đơn hàng đã được gửi thành công qua PayPal", response.data);
  
      // Phát âm thanh khi thanh toán thành công
      
  
      localStorage.removeItem("cart");
      setIsSuccess(true);
      setErrorMessage("");
    } catch (error) {
      console.error("Lỗi khi gửi đơn hàng qua PayPal", error);
      setErrorMessage(
        "Có lỗi xảy ra khi thanh toán qua PayPal. Vui lòng thử lại."
      );
    }
  };
  

  const handlePayPalError = (error) => {
    console.error("Lỗi thanh toán:", error);
    setErrorMessage(
      "Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại."
    );
  };

  const handleContinueShopping = () => {
    navigate("/");
  };

  return (
    <>
      <MainMenu />
      <div className="checkout-container my-5">
        <h1 className="checkout-title text-center text-success">
          <i className="bi bi-cart"></i> THANH TOÁN{" "}
          <i className="fa-solid fa-money-check-dollar"></i>
        </h1>

        {isSuccess ? (
          <div className="alert alert-success text-center" role="alert">
            <h4 className="alert-heading">Thanh toán thành công!</h4>
            <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được xác nhận.</p>
            <button className="btn btn-danger" onClick={handleContinueShopping}>
              Tiếp tục mua hàng
            </button>
          </div>
        ) : (
          <>
            {errorMessage && (
              <div className="alert alert-danger text-center">
                {errorMessage}
              </div>
            )}
            <div className="row">
              <div className="col-md-9">
                <div className="table-responsive">
                  <table className="table table-bordered text-center align-middle checkout-table">
                    <thead className="table-danger">
                      <tr>
                        <th>Mã</th>
                        <th>Hình</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                      </tr>
                    </thead>
                    <tbody>
                      {cartItems.map((item, index) => (
                        <tr key={index}>
                          <td className="text-center">{item.id}</td>
                          <td>
                            <img
                              className="img-fluid cart-img"
                              src={`http://localhost:8000/images/products/${item.image}`}
                              alt={item.name}
                            />
                          </td>
                          <td>{item.name}</td>
                          <td>
                            {new Intl.NumberFormat("vi-VN", {
                              style: "currency",
                              currency: "VND",
                            }).format(item.price)}{" "}
                          </td>
                          <td>{item.quantity}</td>
                          <td>
                            {new Intl.NumberFormat("vi-VN", {
                              style: "currency",
                              currency: "VND",
                            }).format(item.price * item.quantity)}{" "}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
              <div className="col-md-3">
                <div className="total-price text-red">
                  <strong>
                    Tổng tiền:{" "}
                    {new Intl.NumberFormat( {
                      style: "currency",
                      currency: "VND",
                    }).format(
                      cartItems.reduce(
                        (total, item) => total + item.price * item.quantity,
                        0
                      )
                    )}{" "}
                    VND
                  </strong>
                </div>
              </div>
            </div>

            {user ? (
              <>
                <h4 className="text-center mt-5">Thông tin giao hàng</h4>
                <form onSubmit={handleSubmit}>
                  <div className="row">
                    <div className="col-md-6">
                      <div className="mb-3">
                        <label className="form-label">Họ tên:</label>
                        <input
                          type="text"
                          className="form-control"
                          name="delivery_name"
                          value={formData.delivery_name}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-md-6">
                      <div className="mb-3">
                        <label className="form-label">Email:</label>
                        <input
                          type="email"
                          className="form-control"
                          name="delivery_email"
                          value={formData.delivery_email}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                  </div>

                  <div className="row">
                    <div className="col-md-6">
                      <div className="mb-3">
                        <label className="form-label">Điện thoại:</label>
                        <input
                          type="text"
                          className="form-control"
                          name="delivery_phone"
                          value={formData.delivery_phone}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-md-6">
                      <div className="mb-3">
                        <label className="form-label">Địa chỉ:</label>
                        <input
                          type="text"
                          className="form-control"
                          name="delivery_address"
                          value={formData.delivery_address}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                  </div>

                  <div className="row">
                    <div className="col-md-6">
                      <div className="mb-3">
                        <label className="form-label">Giới tính:</label>
                        <select
                          className="form-control"
                          name="delivery_gender"
                          value={formData.delivery_gender}
                          onChange={handleChange}
                        >
                          <option value="male">Nam</option>
                          <option value="female">Nữ</option>
                          <option value="other">Khác</option>
                        </select>
                      </div>
                    </div>
                    <div className="col-md-6">
                      <div className="mb-3">
                        <label className="form-label">Ghi chú:</label>
                        <textarea
                          className="form-control"
                          name="note"
                          value={formData.note}
                          onChange={handleChange}
                        />
                      </div>
                    </div>
                  </div>

                  <div className="payment-options">
                    <h5>Phương thức thanh toán</h5>

                    <div
                      className="form-check"
                      onClick={() => setPaymentMethod("cash")}
                    >
                      <input
                        className="form-check-input"
                        type="radio"
                        name="paymentMethod"
                        value="cash"
                        checked={paymentMethod === "cash"}
                        onChange={(e) => setPaymentMethod(e.target.value)}
                        id="paymentCash"
                      />
                      <label className="form-check-label" htmlFor="paymentCash">
                        <span className="icon">💵</span>
                        Thanh toán khi nhận hàng
                      </label>
                    </div>

                    <div
                      className="form-check"
                      onClick={() => setPaymentMethod("paypal")}
                    >
                      <input
                        className="form-check-input"
                        type="radio"
                        name="paymentMethod"
                        value="paypal"
                        checked={paymentMethod === "paypal"}
                        onChange={(e) => setPaymentMethod(e.target.value)}
                        id="paymentPayPal"
                      />
                      <label
                        className="form-check-label"
                        htmlFor="paymentPayPal"
                      >
                        <span className="icon">💳</span>
                        Thanh toán qua PayPal
                      </label>
                    </div>

                    {paymentMethod === "paypal" && (
                      <div className="paypal-button">
                        <PayPalButton
                          amount={(
                            cartItems.reduce(
                              (total, item) =>
                                total + item.price * item.quantity,
                              0
                            ) / exchangeRate
                          ).toFixed(2)}
                          onSuccess={handlePayPalSuccess}
                          onError={handlePayPalError}
                        />
                      </div>
                    )}
                  </div>

                  <br />
                  <button className="btn btn-primary" type="submit">
                    Thanh toán
                  </button>
                </form>
              </>
            ) : (
              <div className="alert alert-danger" role="alert">
                Bạn cần đăng nhập để tiến hành thanh toán.
              </div>
            )}
          </>
        )}
       <audio id="success-audio" preload="auto">
  <source src="/sound/success-sound.mp3" type="audio/mpeg" />
  <source src="/sound/success-sound.ogg" type="audio/ogg" />
  Trình duyệt của bạn không hỗ trợ phần tử audio.
</audio>



         
      </div>
      <Footer />
    </>
  );
};

export default CheckoutPage;
