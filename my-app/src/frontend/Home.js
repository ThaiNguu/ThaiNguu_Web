import React from 'react';
import ListBanner from '../components/ListBanner';
import Footer from '../components/Footer';
import LastPost from '../components/LastPost';
import ProductNew from '../components/ProductNew';
import ProductSale from '../components/ProductSale';
import MainMenu from '../components/main-menu'; // Đảm bảo đường dẫn đúng

const Home = () => {
  return (
    <div>
      <MainMenu />
      <ListBanner />
      <ProductSale />
      <ProductNew />
      <LastPost />
      <Footer />
    </div>
  );
};

export default Home;
