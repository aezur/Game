import axios from 'axios';

export function useAxios() {
    const axiosInstance = axios.create({
        baseURL: '/api',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        withCredentials: true,        
    });

    return { axios: axiosInstance };
}