import React, { createContext, useContext, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const navigate = useNavigate();

  const [user, setUser] = useState(null);
  const [authLoading, setAuthLoading] = useState(false);
  const [authError, setAuthError] = useState("");
  const [fieldErrors, setFieldErrors] = useState({});

  // Regex for validations
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
  const usernameRegex = /^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]{3,20}(?<![_.])$/;

  // Validation for signup
  const validateSignup = ({ username, email, password }) => {
    if (!usernameRegex.test(username)) {
      return "Username must be 3-20 characters, no special characters except '.' or '_'";
    }
    if (!emailRegex.test(email)) {
      return "Invalid email address";
    }
    if (!passwordRegex.test(password)) {
      return "Password must be at least 8 characters, include uppercase, lowercase, number, and special character";
    }
    return "";
  };

  // Validation for login
  const validateLogin = ({ email, password }) => {
    const errors = {};
    if (!email) {
      errors.email = "Email is required.";
    } else if (!emailRegex.test(email)) {
      errors.email = "Please enter a valid email.";
    }
    if (!password) {
      errors.password = "Password is required.";
    }
    return errors;
  };

  const signup = async (formData) => {
    setAuthError("");
    const validationMessage = validateSignup(formData);
    if (validationMessage) {
      setAuthError(validationMessage);
      return false;
    }

    setAuthLoading(true);
    try {
      const res = await axios.post("http://127.0.0.1:8000/api/register", formData);
      setUser(res.data.user);
      navigate("/otp-verification");
      return true;
    } catch (err) {
      setAuthError(err.response?.data?.error || "Something went wrong.");
      return false;
    } finally {
      setAuthLoading(false);
    }
  };

  const login = async (formData) => {
    setAuthError("");
    setFieldErrors({});
    const validationErrors = validateLogin(formData);
    if (Object.keys(validationErrors).length > 0) {
      setFieldErrors(validationErrors);
      return false;
    }

    setAuthLoading(true);
    try {
      const response = await axios.post("http://127.0.0.1:8000/api/login", formData);
      const { token, user } = response.data;

      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
      setUser(user);
      navigate("/home");
      return true;
    } catch (err) {
      setAuthError(err.response?.data?.message || "Login failed. Try again.");
      return false;
    } finally {
      setAuthLoading(false);
    }
  };

  const signInWithGoogle = async () => {
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/auth/google");
      window.location.href = res.data.url;
    } catch (error) {
      console.error("Google login redirect error:", error);
    }
  };

  const signInWithGithub = async () => {
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/auth/github");
      window.location.href = res.data.url;
    } catch (error) {
      console.error("GitHub login redirect error:", error);
    }
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        authError,
        authLoading,
        fieldErrors,
        signup,
        login,
        signInWithGoogle,
        signInWithGithub,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
