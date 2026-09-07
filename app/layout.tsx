import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "Tracking Renungan Sekolah Minggu",
  description: "Sistem tracking renungan sekolah minggu GKPPD",
};

export default function RootLayout({ children }: LayoutProps<"/">) {
  return <html lang="id"><body>{children}</body></html>;
}
