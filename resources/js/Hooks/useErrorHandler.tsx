export function useErrorHandler() {
  const handleError = (error: any) => {
    console.error(error);
    if (error.response) {
      console.info(error.response.data);
      console.info(error.response.status);
      console.info(error.response.headers);
    } else if (error.request) {
      console.info(error.request);
    } else {
      console.info("Error", error.message);
    }
  };

  return { handleError };
}
