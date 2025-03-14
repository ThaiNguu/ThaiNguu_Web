import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import ProductCard from '../../components/ProductCard';
import MainMenu from '../../components/main-menu';
import Footer from '../../components/Footer';
import '../../css/AllProduct.css';
import '../../css/phantrang.css';


const AllProducts = () => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [productsPerPage] = useState(6);
  const [activeCategory, setActiveCategory] = useState(null); 
  const navigate = useNavigate();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/product');
        const filteredProducts = response.data.data.filter(product => product.status === 1);
        const sortedProducts = filteredProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        setProducts(sortedProducts);
      } catch (error) {
        console.error("Error fetching products: ", error);
      }
    };

    const fetchCategories = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/category');
        const activeCategories = response.data.data.filter(category => category.status === 1);
        setCategories(activeCategories);
      } catch (error) {
        console.error("Error fetching categories: ", error);
      }
    };

    fetchProducts();
    fetchCategories();
  }, []);

  // Toggle danh mục khi nhấn vào tiêu đề danh mục
  const toggleCategory = (categoryId) => {
    setActiveCategory(activeCategory === categoryId ? null : categoryId);
  };

  const renderCategories = (parentId) => {
    return categories
      .filter(category => category.parent_id === parentId)
      .map(category => (
        <li key={category.id} className="list-group-item">
          {/* Sự kiện mở/đóng accordion */}
          <div 
            onClick={() => toggleCategory(category.id)} 
            style={{ cursor: 'pointer', fontWeight: 'bold' }}
          >
            {category.name}
          </div>
  
          {/* Điều hướng sang trang danh mục khi nhấn vào nút */}
          <button 
            onClick={() => handleCategoryClick(category.slug)} 
            style={{ marginLeft: '10px', cursor: 'pointer' }}
          >
            Xem danh mục
          </button>
  
          {/* Nếu danh mục này đang được mở, hiển thị các danh mục con */}
          {activeCategory === category.id && (
            <ul className="list-group">
              {renderCategories(category.id)}
            </ul>
          )}
        </li>
      ));
  };
  
  
  const handleCategoryClick = (slug) => {
    navigate(`/danh-muc/${slug}`);
  };

  // Tính toán phân trang
  const indexOfLastProduct = currentPage * productsPerPage;
  const indexOfFirstProduct = indexOfLastProduct - productsPerPage;
  const currentProducts = products.slice(indexOfFirstProduct, indexOfLastProduct);
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
    <div className="product">
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

          <div className="col-md-9">
            <h1>Tất cả sản phẩm</h1>
            <div className="row">
              {currentProducts.length === 0 ? (
                <p>Không có sản phẩm nào</p>
              ) : (
                currentProducts.map(product => (
                  <div key={product.id} className="col-md-4 mb-3">
                    <ProductCard productItem={product} />
                  </div>
                ))
              )}
            </div>

            {/* Phân trang */}
            <div className="pagination d-flex justify-content-center mt-4">
              <button
                className="btn btn-primary me-2"
                onClick={handlePrevPage}
                disabled={currentPage === 1}
              >
                Previous
              </button>
              <span className="align-self-center">Page {currentPage} of {pageCount}</span>
              <button
                className="btn btn-primary ms-2"
                onClick={handleNextPage}
                disabled={currentPage === pageCount}
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </div>
  );
};

export default AllProducts;
 