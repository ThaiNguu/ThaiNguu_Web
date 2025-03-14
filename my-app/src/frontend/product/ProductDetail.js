import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';
import Footer from '../../components/Footer';
import MainMenu from '../../components/main-menu';
import ProductCard from '../../components/ProductCard';
import '../../css/productdetail.css'; // Thêm file CSS riêng nếu cần thiết

const ProductDetail = () => {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [relatedProducts, setRelatedProducts] = useState([]);
  const [quantity, setQuantity] = useState(1);

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const response = await fetch(`http://localhost:8000/api/product/${id}`);
        const data = await response.json();
  
        if (response.ok) {
          setProduct(data);
          const categoryId = data.category_id;
          if (categoryId) {
            fetchRelatedProducts(categoryId);
          } else {
            console.error('Category not found in product data');
          }
        } else {
          console.error('Error fetching product:', data.message);
        }
      } catch (error) {
        console.error('Fetch error:', error);
      } finally {
        setLoading(false);
      }
    };

    const fetchRelatedProducts = async (categoryId) => {
      try {
        const response = await fetch('http://localhost:8000/api/product');
        const data = await response.json();
  
        if (response.ok) {
          const filteredProducts = data.data.filter(
            (item) => item.category_id === categoryId && item.id !== parseInt(id) && item.status === 1
          );
          const limitedProducts = filteredProducts.slice(0, 4);
          setRelatedProducts(limitedProducts);
        } else {
          console.error('Error fetching related products:', data.message);
        }
      } catch (error) {
        console.error('Error fetching related products:', error);
      }
    };
  
    fetchProduct();
  }, [id]);

  const increaseQuantity = () => {
    setQuantity((prevQuantity) => prevQuantity + 1);
  };

  const decreaseQuantity = () => {
    setQuantity((prevQuantity) => (prevQuantity > 1 ? prevQuantity - 1 : 1));
  };

  const handleAddToCart = () => {
    if (!product) return;
  
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingProductIndex = cart.findIndex(item => item.id === product.id);
  
    if (existingProductIndex >= 0) {
      cart[existingProductIndex].quantity += quantity;
    } else {
      const productToAdd = {
        id: product.id,
        name: product.name,
        price: product.pricesale > 0 && product.pricesale < product.price ? product.pricesale : product.price,
        image: product.image,
        quantity: quantity
      };
      cart.push(productToAdd);
    }
  
    localStorage.setItem('cart', JSON.stringify(cart));
  
    // Phát sự kiện tùy chỉnh để thông báo cập nhật giỏ hàng
    const event = new CustomEvent('cartUpdated', { detail: cart });
    window.dispatchEvent(event);
  
    alert(`Đã thêm ${quantity} sản phẩm vào giỏ hàng!`);
  };
  

  const handleBuyNow = () => {
    alert(`Bạn đã mua ${quantity} sản phẩm!`);
  };

  if (loading) {
    return <div className="text-center">Loading...</div>;
  }

  if (!product) {
    return <div className="text-center">Product not found</div>;
  }

  const { name, detail, description, price, pricesale, image } = product;

  return (
    <div className="product-detail-container">
      <MainMenu />
      
      <div className="container my-4">
        <h1 className="my-4 text-center">{name}</h1>
        <div className="row">
          <div className="col-md-6">
            <img
              src={`http://localhost:8000/images/products/${image}`}
              alt={name}
              className="img-fluid rounded shadow-lg"
            />
          </div>
          <div className="col-md-6">
            <h2 className="mt-3">{name}</h2>
            <p className="text-muted">{detail}</p>
            <p>{description}</p>
            <div className="price mb-3">
              {pricesale > 0 && pricesale < price ? (
                <>
                  <span className="text-danger display-5">{pricesale.toLocaleString('vi-VN')} VNĐ</span>
                  <del className="ms-2 text-muted">{price.toLocaleString('vi-VN')} VNĐ</del>
                </>
              ) : (
                <span className="display-5">{price.toLocaleString('vi-VN')} VNĐ</span>
              )}
            </div>
            <div className="d-flex align-items-center mb-3 quantity-control">
              <button className="btn btn-warning" onClick={decreaseQuantity}>-</button>
              <input
                type="number"
                value={quantity}
                onChange={(e) => {
                  const value = Math.max(1, Number(e.target.value)); // Đảm bảo giá trị không nhỏ hơn 1
                  setQuantity(value);
                }}
                className="form-control mx-2"
                style={{ width: '60px' }}
              />
              <button className="btn btn-warning" onClick={increaseQuantity}>+</button>
            </div>

            <div>
              <button className="btn btn-warning me-2" onClick={handleAddToCart}>Thêm vào giỏ hàng</button>
              <button className="btn btn-success" onClick={handleBuyNow}>Mua ngay</button>
            </div>
          </div>
        </div>

        <h3 className="mt-4">Sản phẩm liên quan</h3>
        <div className="row">
          {relatedProducts.length > 0 ? (
            relatedProducts.map((relatedProduct) => (
              <div key={relatedProduct.id} className="col-md-3 mb-4">
                <ProductCard productItem={relatedProduct} />
              </div>
            ))
          ) : (
            <div className="col-12 text-center">Không có sản phẩm liên quan.</div>
          )}
        </div>
      </div>
      
      <Footer />
    </div>
  );
};

export default ProductDetail;
