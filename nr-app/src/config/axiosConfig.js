// src/config/axiosConfig.js
import axios from 'axios';

// Configura la URL base
const axiosInstance = axios.create({
    baseURL: 'http://10.0.0.10:8000/api',
});

export default axiosInstance;
