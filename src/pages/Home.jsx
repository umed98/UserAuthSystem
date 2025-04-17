import React from "react";
import { signOut } from "firebase/auth";
import { auth } from "../firebase"; // Adjust this path if your firebase config is elsewhere
import { useNavigate } from "react-router-dom"
const Home = () => {

  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      await signOut(auth);
      navigate("/login"); // redirect to login page
    } catch (error) {
      console.error("Error logging out:", error);
    }
  };

  return (
    <div className="p-4 text-center flex flex-col w-full h-screen justify-center items-center">
      <h1 className="text-3xl font-bold">Welcome to the Home Page!</h1>
      <button  onClick={handleLogout} className="bg-red-500 text-lg text-white font-medium py-2 px-6 rounded-md mt-5 cursor-pointer">Logout</button>
    </div>
  );
};

export default Home;
