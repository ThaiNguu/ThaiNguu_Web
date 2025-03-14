import React, { useState, useEffect } from "react";
import axios from "axios";
import ProductCard from "./ProductCard";
import { Link } from "react-router-dom";
const ProductSale = () => {
  const [productSale, setProductSale] = useState([]);

  useEffect(() => {
    axios
      .get("http://localhost:8000/api/product")
      .then((response) => {
        const allProducts = response.data.data;
        // Lọc sản phẩm có pricesale lớn hơn 0 và status bằng 1
        const saleProducts = allProducts.filter(
          (product) => product.pricesale > 0 && product.status === 1
        );
        // Sắp xếp theo ngày tạo mới nhất trước
        const sortedProducts = saleProducts.sort(
          (a, b) => new Date(b.created_at) - new Date(a.created_at)
        );
        // Lấy 4 sản phẩm đầu tiên
        const latestProducts = sortedProducts.slice(0, 4);
        setProductSale(latestProducts);
      })
      .catch((error) => {
        console.error("Lỗi khi lấy dữ liệu sản phẩm:", error);
      });
  }, []);

  return (
    <section id="product-sale" className="p-3">
      <h2 className="text-center text-danger">
        <i className="fas fa-bolt"></i> FLASH SALE
      </h2>
      <div className="row">
        {productSale.length > 0 ? (
          productSale.map((productItem) => (
            <div key={productItem.id} className="col-sm-3 my-2">
              <Link to={`/chi-tiet-san-pham/${productItem.slug}`}>
                <ProductCard productItem={productItem} />
              </Link>
            </div>
          ))
        ) : (
          <div className="col-12 text-center">
            <p>Không có sản phẩm nào trong chương trình khuyến mãi.</p>
          </div>
        )}
      </div>
    </section>
  );
};

export default ProductSale;
