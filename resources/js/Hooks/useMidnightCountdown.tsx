import { router } from "@inertiajs/core";
import { useEffect, useMemo, useState } from "react";

export function useMidnightCountdown() {
  const [counter, setCounter] = useState(
    // Seconds to midnight
    24 * 60 * 60 -
      new Date().getHours() * 60 * 60 -
      new Date().getMinutes() * 60 -
      new Date().getSeconds()
  );

  useEffect(() => {
    // If the counter is 0, reload the page to refresh the gladiator list
    if (counter <= 0) {
      router.reload({ preserveScroll: true });
    }
    const timer =
      counter > 0 && setInterval(() => setCounter(counter - 1), 1000);
    return () => {
      if (timer) clearInterval(timer);
    };
  }, [counter]);

  const formattedCounter = useMemo(() => {
    const hours = Math.floor(counter / 3600);
    const minutes = Math.floor((counter - hours * 3600) / 60);
    const seconds = counter - hours * 3600 - minutes * 60;
    return `${hours.toString().padStart(2, "0")}:${minutes
      .toString()
      .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
  }, [counter]);

  return {
    formattedCounter,
    counter,
  };
}
