import React, { useEffect, useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import Footer from '../components/Footer';
import MainMenu from '../components/main-menu';
import { useNavigate } from 'react-router-dom';
import '../css/CartPage.css';

const CartPage = () => {
  const [cart, setCart] = useState([]);
  const navigate = useNavigate();
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    const cartData = JSON.parse(localStorage.getItem('cart')) || [];
    setCart(cartData);

    const userToken = localStorage.getItem('token');
    setIsLoggedIn(!!userToken);
  }, []);

  const updateCart = (updatedCart) => {
    setCart(updatedCart);
    localStorage.setItem('cart', JSON.stringify(updatedCart));

    const cartEvent = new CustomEvent('cartUpdated', { detail: updatedCart });
    window.dispatchEvent(cartEvent);
  };

  const increaseQuantity = (productId) => {
    const updatedCart = cart.map(item => {
      if (item.id === productId) {
        return { ...item, quantity: item.quantity + 1 };
      }
      return item;
    });
    updateCart(updatedCart);
  };

  const decreaseQuantity = (productId) => {
    const updatedCart = cart.map(item => {
      if (item.id === productId && item.quantity > 1) {
        return { ...item, quantity: item.quantity - 1 };
      }
      return item;
    });
    updateCart(updatedCart);
  };

  const handleQuantityChange = (productId, value) => {
    const newQuantity = Math.max(1, parseInt(value, 10) || 1);
    const updatedCart = cart.map(item => {
      if (item.id === productId) {
        return { ...item, quantity: newQuantity };
      }
      return item;
    });
    updateCart(updatedCart);
  };

  const handleRemoveFromCart = (productId) => {
    const updatedCart = cart.filter(item => item.id !== productId);
    updateCart(updatedCart);
  };

  const handleCheckout = () => {
    if (!isLoggedIn) {
      alert("Bạn cần đăng nhập để có thể thanh toán.");
      navigate('/login');
      return;
    }
    
    navigate('/checkout');
  };

  const totalPrice = cart.reduce((total, item) => total + item.price * item.quantity, 0);

  return (
    <div className="custom-cart-page">
      <MainMenu />
      
      <div className="container my-4">
        <h1 className="text-center mb-5 text-success">Giỏ hàng của bạn <i className="fa-solid fa-cart-shopping"></i></h1>
        
        <div className="row">
          {cart.length > 0 ? (
            <>
              <div className="col-md-8">
                <div>
                  {cart.map((item) => (
                    <div key={item.id} className="row mb-4 custom-cart-item">
                      <div className="col-md-4">
                        <img
                          src={`http://localhost:8000/images/products/${item.image}`}
                          alt={item.name}
                          className="img-fluid rounded shadow-lg custom-cart-image"
                        />
                      </div>
                      <div className="col-md-8">
                        <h4>{item.name}</h4>
                        <p className="text-red">Giá: {item.price.toLocaleString('vi-VN')} VNĐ</p>
                        <div className="d-flex align-items-center justify-content-between">
                          <div className="d-flex align-items-center">
                            <button className="btn btn-warning custom-quantity-btn" onClick={() => decreaseQuantity(item.id)}>-</button>
                            <input
                              type="number"
                              value={item.quantity}
                              onChange={(e) => handleQuantityChange(item.id, e.target.value)}
                              className="form-control mx-2 custom-quantity-input"
                              style={{ width: '60px' }}
                            />
                            <button className="btn btn-warning custom-quantity-btn" onClick={() => increaseQuantity(item.id)}>+</button>
                          </div>
                          <button
                            className="btn btn-danger custom-remove-btn"
                            onClick={() => handleRemoveFromCart(item.id)}
                          >
                            Xóa sản phẩm
                          </button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              <div className="col-md-4">
                <div className="custom-shop-info mb-4">
                  <p className="text-dark">
                    <strong>Cảm ơn bạn đã chọn cửa hàng chúng tôi giữa vô vàn lựa chọn khác – bạn vừa chứng minh mình có gu mua sắm đỉnh cao rồi đấy!</strong>
                  </p>
                </div>
                <h3 className="text-end custom-total-price text-danger">
                  Tổng cộng: {totalPrice.toLocaleString('vi-VN')} VNĐ
                </h3>
                <div className="text-end">
                  <button className="btn btn-success custom-checkout-btn" onClick={handleCheckout}>Thanh toán</button>
                </div>
              </div>
            </>
          ) : (
            <div className="col-12 text-center custom-empty-cart">
              <h2>Giỏ hàng trống.</h2>
            </div>
          )}
        </div>
      </div>

      <Footer />
    </div>
  );
};

export default CartPage;
