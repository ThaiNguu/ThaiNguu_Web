import React, { useState, useEffect } from 'react';
import axios from 'axios';
import ProductCard from './ProductCard'; // Đảm bảo đường dẫn đúng
import { Link } from 'react-router-dom';
const ProductNew = () => {
  const [productNew, setProductNew] = useState([]);

  useEffect(() => {
    axios.get('http://localhost:8000/api/product')
      .then(response => {
        const allProducts = response.data.data;
        // Lọc sản phẩm có status bằng 1
        const newProducts = allProducts.filter(product => product.status === 1);
        // Sắp xếp theo ngày tạo mới nhất trước
        const sortedProducts = newProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        // Giới hạn chỉ lấy 8 sản phẩm
        const limitedProducts = sortedProducts.slice(0, 8);
        // Cập nhật danh sách sản phẩm mới nhất
        setProductNew(limitedProducts);
      })
      .catch(error => {
        console.error('Lỗi khi lấy dữ liệu sản phẩm:', error);
      });
  }, []);

  return (
    <section id="product-new" className="p-3">
      <h2 className="text-center text-info">
        <i className="fas fa-star"></i> SẢN PHẨM MỚI NHẤT
      </h2>
      <div className="row">
        {productNew.length > 0 ? (
          productNew.map((product) => (
            <div key={product.id} className="col-sm-3 my-2">
             <Link to={`/chi-tiet-san-pham/${product.slug}`}>
  <ProductCard productItem={product} />
</Link>
            </div>
          ))
        ) : (
          <div className="col-12 text-center">
            <p>Không có sản phẩm mới nào.</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default ProductNew;
