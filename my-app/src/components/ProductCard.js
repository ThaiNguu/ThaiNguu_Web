import React from 'react';
import { Link } from 'react-router-dom';
import '../css/productcard.css';
import 'bootstrap/dist/css/bootstrap.min.css';

const ProductCard = ({ productItem }) => {
  if (!productItem) {
    return <div>Product not found</div>;
  }   

  const { name, price, pricesale, image, id } = productItem;

  return (
    <div className="card card-custom">
      <Link to={`/chi-tiet-san-pham/${id}`} className='text-dark'>
        <img
          src={`http://localhost:8000/images/products/${image}`}
          alt={image}
          className="card-img-top"
          style={{
            width: '100%',
            height: 'auto', // Tự động điều chỉnh chiều cao theo chiều ngang
            height: '500px', // Giới hạn chiều cao tối đa nếu cần
            objectFit: 'cover' // Cắt hình để phù hợp với khung hình
          }}
        />
        <div className="card-body">
          <h4 className="card-title" style={{ whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
            <b>{name}</b>
          </h4>
          <div className="price_sale text-danger d-flex align-items-center justify-content-between">
            <div className="price col-8">
              {pricesale > 0 && pricesale < price ? (
                <>
                  <span>{pricesale.toLocaleString('vi-VN')}</span>
                  <del className="ms-2">{price.toLocaleString('vi-VN')}</del>
                </>
              ) : (
                <span>{price.toLocaleString('vi-VN')}</span>
              )}
            </div>
          </div>
        </div>
      </Link>
    </div>
  );
};

export default ProductCard;
