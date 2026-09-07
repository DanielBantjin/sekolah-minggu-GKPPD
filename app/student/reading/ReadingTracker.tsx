"use client";
import { useEffect, useState } from "react";

export function ReadingTracker({ reflectionId }: { reflectionId: number }) {
  const [seconds, setSeconds] = useState(0);
  useEffect(() => {
    const interval = window.setInterval(() => {
      setSeconds((current) => current + 30);
      void fetch("/api/reading-track", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ reflectionId, duration: 30 }) });
    }, 30000);
    return () => window.clearInterval(interval);
  }, [reflectionId]);
  return <p className="reading-timer">Durasi membaca: {seconds} detik</p>;
}
