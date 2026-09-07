"use client";

import { Area, AreaChart, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from "recharts";

type ChartItem = { label: string; value: number; color: string };

export function AnalyticsChart({ title, items, format = "number" }: { title: string; items: ChartItem[]; format?: "number" | "currency" }) {
  const data = items.length ? items : Array.from({ length: 6 }, (_, index) => ({ label: `Data ${index + 1}`, value: 0, color: "#cbd5e1" }));
  const color = items[0]?.color ?? "#cbd5e1";
  const gradientId = `gradient-${title.toLowerCase().replaceAll(" ", "-")}`;
  const formatValue = (value: number) => format === "currency" ? `Rp ${(value / 1000).toLocaleString("id-ID")}k` : value.toLocaleString("id-ID");
  return <div className="chart-card"><div className="chart-heading"><div><h2>{title}</h2><p>{items.length ? "Ringkasan berdasarkan periode terpilih" : "Belum ada nilai pada periode terpilih"}</p></div><span className="chart-legend" style={{ backgroundColor: color }} /></div><div className={`recharts-wrapper${items.length ? "" : " empty-chart"}`}><ResponsiveContainer width="100%" height={250}><AreaChart data={data} margin={{ top: 12, right: 12, left: 0, bottom: 4 }}><defs><linearGradient id={gradientId} x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stopColor={color} stopOpacity={0.38} /><stop offset="100%" stopColor={color} stopOpacity={0.03} /></linearGradient></defs><CartesianGrid stroke="#e2e8f0" strokeDasharray="4 4" vertical={false} /><XAxis dataKey="label" tick={{ fill: "#64748b", fontSize: 11 }} axisLine={false} tickLine={false} /><YAxis tick={{ fill: "#64748b", fontSize: 11 }} axisLine={false} tickLine={false} tickFormatter={formatValue} width={58} /><Tooltip contentStyle={{ border: "1px solid #e2e8f0", borderRadius: 12, boxShadow: "0 10px 25px rgba(15,23,42,.12)" }} formatter={(value) => [formatValue(Number(value)), "Nilai"]} /><Area type="monotone" dataKey="value" stroke={color} strokeWidth={3} fill={`url(#${gradientId})`} dot={{ r: 4, fill: color, stroke: "#fff", strokeWidth: 2 }} activeDot={{ r: 6 }} isAnimationActive={items.length > 0} /></AreaChart></ResponsiveContainer></div></div>;
}
