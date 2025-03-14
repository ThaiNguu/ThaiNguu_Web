import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useLocation } from 'react-router-dom';
import ProductCard from '../components/ProductCard';
import MainMenu from '../components/main-menu';
import Footer from '../components/Footer';

const SearchProduct = () => {
    const location = useLocation();
    const queryParams = new URLSearchParams(location.search);
    const query = queryParams.get('query');

    const [products, setProducts] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [error, setError] = useState('');
    const itemsPerPage = 8;

    useEffect(() => {
        const fetchProducts = async () => {
            if (query) {
                try {
                    const response = await axios.get(`http://localhost:8000/api/products/search?query=${query}`);
                    setProducts(response.data);
                    setTotalPages(Math.ceil(response.data.length / itemsPerPage));
                    setError('');
                } catch (err) {
                    setError('Không tìm thấy sản phẩm');
                    setProducts([]);
                }
            }
        };

        fetchProducts();
    }, [query]);

    const handlePageChange = (page) => {
        if (page >= 1 && page <= totalPages) {
            setCurrentPage(page);
        }
    };

    const paginatedProducts = products.slice(
        (currentPage - 1) * itemsPerPage,
        currentPage * itemsPerPage
    );

    return (
        <>
            <MainMenu />
            <div className="mt-4 px-3"> {/* Remove 'container' class and add padding */}
                <h1 className="mb-4">Kết quả tìm kiếm: {query}</h1>
                {error && <p className="text-danger">{error}</p>}
                <div className="row gx-3"> {/* Use 'gx-3' for horizontal gutter spacing */}
                    {paginatedProducts.length > 0 ? (
                        paginatedProducts.map(product => (
                            <div className="col-6 col-md-4 col-lg-3 mb-4" key={product.id}> {/* Adjust column classes */}
                                <ProductCard productItem={product} />
                            </div>
                        ))
                    ) : (
                        <p>Không tìm thấy sản phẩm</p>
                    )}
                </div>
                <div className="pagination mt-4">
                    <button
                        className="btn btn-outline-primary me-2"
                        onClick={() => handlePageChange(currentPage - 1)}
                        disabled={currentPage === 1}
                    >
                        Trước
                    </button>
                    <span>Trang {currentPage} / {totalPages}</span>
                    <button
                        className="btn btn-outline-primary ms-2"
                        onClick={() => handlePageChange(currentPage + 1)}
                        disabled={currentPage === totalPages}
                    >
                        Sau
                    </button>
                </div>
            </div>
            <Footer />
        </>
    );
};

export default SearchProduct;
