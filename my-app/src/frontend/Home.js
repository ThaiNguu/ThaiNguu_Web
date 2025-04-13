import React from "react";
import ListBanner from "../components/ListBanner";
import Footer from "../components/Footer";
import LastPost from "../components/LastPost";
import ProductNew from "../components/ProductNew";
import ProductSale from "../components/ProductSale";
import MainMenu from "../components/main-menu";




import ChatbotUser from "./ChatbotUser";

const Home = () => {
  return (
    <div>
      <MainMenu />
      <ListBanner />
      <ProductSale />
      <ProductNew />
      <LastPost />
      <Footer />
      <ChatbotUser />
    </div>
  );
};

export default Home;
