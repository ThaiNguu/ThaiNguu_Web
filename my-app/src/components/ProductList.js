import React, { useEffect, useState } from "react";
import axios from "axios";
import { useParams, useNavigate } from "react-router-dom";
import ProductCard from "./ProductCard";
import MainMenu from "../components/main-menu";
import Footer from "../components/Footer";
import "../css/ListProduct.css"; // Thêm file CSS cho các style bổ sung

const ListProduct = () => {
  const { slug } = useParams();
  const [products, setProducts] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [productsPerPage] = useState(6);
  const [categories, setCategories] = useState([]);
  const [categoryName, setCategoryName] = useState("");
  const [activeCategory, setActiveCategory] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await axios.get(
          `http://localhost:8000/api/categories/${slug}/products`
        );
        // Sắp xếp sản phẩm theo thời gian tạo mới nhất
        const sortedProducts = response.data.products.sort(
          (a, b) => new Date(b.created_at) - new Date(a.created_at)
        );
        setProducts(sortedProducts);
        setCategoryName(response.data.category.name);
      } catch (error) {
        console.error("Error fetching products: ", error);
      }
    };

    const fetchCategories = async () => {
      try {
        const response = await axios.get("http://localhost:8000/api/category");
        const activeCategories = response.data.data.filter(
          (category) => category.status === 1
        );
        setCategories(activeCategories);
      } catch (error) {
        console.error("Error fetching categories: ", error);
      }
    };

    fetchProducts();
    fetchCategories();
  }, [slug]);

  const toggleCategory = (categoryId) => {
    setActiveCategory(activeCategory === categoryId ? null : categoryId);
  };

  const renderCategories = (parentId) => {
    return categories
      .filter((category) => category.parent_id === parentId)
      .map((category) => (
        <li key={category.id} className="list-group-item">
          <div
            className="accordion-header"
            onClick={() => toggleCategory(category.id)}
          >
            {category.name}
          </div>

          <button
            className="view-category-button"
            onClick={() => handleCategoryClick(category.slug)}
          >
            Xem danh mục
          </button>

          {activeCategory === category.id && (
            <ul className="list-group">{renderCategories(category.id)}</ul>
          )}
        </li>
      ));
  };

  const handleCategoryClick = (slug) => {
    navigate(`/danh-muc/${slug}`);
  };
  const indexOfLastProduct = currentPage * productsPerPage;
  const indexOfFirstProduct = indexOfLastProduct - productsPerPage;
  const currentProducts = products.slice(
    indexOfFirstProduct,
    indexOfLastProduct
  );
  const pageCount = Math.ceil(products.length / productsPerPage);

  const handleNextPage = () => {
    if (currentPage < pageCount) {
      setCurrentPage(currentPage + 1);
    }
  };

  const handlePrevPage = () => {
    if (currentPage > 1) {
      setCurrentPage(currentPage - 1);
    }
  };
  return (
    <div className="list-product">
      <MainMenu />
      <div className="container-fluid">
        <div className="row">
          <div className="col-md-3 category-column">
            <h2>Danh mục</h2>
            <ul className="list-group">
              {categories.length === 0 ? (
                <li className="list-group-item">Không có danh mục nào</li>
              ) : (
                renderCategories(0)
              )}
            </ul>
          </div>

          <div className="col-md-9 product-column">
            <h1 className="category-title">{categoryName}</h1>
            {products.length === 0 ? (
              <p className="no-products">Không có sản phẩm nào</p>
            ) : (
              <div className="row">
                {currentProducts.map((product) => (
                  <div key={product.id} className="col-md-4 mb-4">
                    <ProductCard productItem={product} />
                  </div>
                ))}
                <div className="pagination d-flex justify-content-center mt-4">
                  <button
                    className="btn btn-primary me-2"
                    onClick={handlePrevPage}
                    disabled={currentPage === 1}
                  >
                    Previous
                  </button>
                  <span className="align-self-center">
                    Page {currentPage} of {pageCount}
                  </span>
                  <button
                    className="btn btn-primary ms-2"
                    onClick={handleNextPage}
                    disabled={currentPage === pageCount}
                  >
                    Next
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
      <Footer />
    </div>
  );
};

export default ListProduct;
