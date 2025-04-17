import { useEffect } from "react";
import { useNavigate } from "react-router-dom";

const OAuthCallback = () => {
  const navigate = useNavigate();

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const token = params.get("token");

    if (token) {
      localStorage.setItem("token", token);
      navigate("/"); // redirect to home or dashboard
    } else {
      navigate("/login");
    }
  }, [navigate]);

  return <div>Logging you in...</div>;
};

export default OAuthCallback;
