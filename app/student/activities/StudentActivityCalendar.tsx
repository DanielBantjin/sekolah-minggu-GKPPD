"use client";

import { useMemo, useState } from "react";

type Activity = {
  id: number;
  title: string;
  date: Date | string;
  startTime?: string | null;
  endTime?: string | null;
  location?: string | null;
  category?: string | null;
  description?: string | null;
};

const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

function formatDateKey(date: Date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

function getMonthGrid(month: Date) {
  const firstDay = new Date(month.getFullYear(), month.getMonth(), 1);
  const startWeekDay = firstDay.getDay();
  const startDate = new Date(firstDay);
  startDate.setDate(firstDay.getDate() - startWeekDay);

  const cells: Date[] = [];
  for (let index = 0; index < 42; index += 1) {
    const current = new Date(startDate);
    current.setDate(startDate.getDate() + index);
    cells.push(current);
  }
  return cells;
}

export function StudentActivityCalendar({ activities }: { activities: Activity[] }) {
  const [visibleMonth, setVisibleMonth] = useState(() => {
    const now = new Date();
    return new Date(now.getFullYear(), now.getMonth(), 1);
  });
  const [selectedDate, setSelectedDate] = useState(() => formatDateKey(new Date()));

  const groupedActivities = useMemo(() => {
    const map = new Map<string, Activity[]>();
    activities.forEach((activity) => {
      const date = new Date(activity.date);
      const key = formatDateKey(date);
      const existing = map.get(key) ?? [];
      existing.push(activity);
      map.set(key, existing);
    });
    return map;
  }, [activities]);

  const monthDays = getMonthGrid(visibleMonth);
  const monthLabel = visibleMonth.toLocaleDateString("id-ID", { month: "long", year: "numeric" });
  const selectedItems = groupedActivities.get(selectedDate) ?? [];

  const moveMonth = (direction: number) => {
    setVisibleMonth((current) => new Date(current.getFullYear(), current.getMonth() + direction, 1));
  };

  return (
    <section className="student-activity-card">
      <div className="student-activity-header">
        <div>
          <p className="eyebrow">Kegiatan</p>
          <h2>Jadwal kegiatan</h2>
        </div>
        <div className="activity-calendar-nav">
          <button type="button" onClick={() => moveMonth(-1)} aria-label="Bulan sebelumnya">‹</button>
          <span>{monthLabel}</span>
          <button type="button" onClick={() => moveMonth(1)} aria-label="Bulan berikutnya">›</button>
        </div>
      </div>

      <div className="activity-calendar-grid" role="grid" aria-label="Kalender kegiatan">
        {dayNames.map((day) => <span key={day} className="calendar-weekday">{day}</span>)}
        {monthDays.map((date) => {
          const key = formatDateKey(date);
          const isCurrentMonth = date.getMonth() === visibleMonth.getMonth();
          const hasActivity = groupedActivities.has(key);
          const isSelected = key === selectedDate;

          return (
            <button
              key={key}
              type="button"
              className={[
                "calendar-day",
                isCurrentMonth ? "current-month" : "muted-month",
                hasActivity ? "has-activity" : "",
                isSelected ? "selected" : "",
              ].join(" ")}
              onClick={() => setSelectedDate(key)}
            >
              <span>{date.getDate()}</span>
              {hasActivity && <small>•</small>}
            </button>
          );
        })}
      </div>

      <div className="activity-detail-panel">
        <h3>{new Date(`${selectedDate}T00:00:00`).toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" })}</h3>
        {selectedItems.length ? (
          <div className="activity-detail-list">
            {selectedItems.map((activity) => (
              <article className="activity-detail-item" key={activity.id}>
                <div className="activity-detail-topline">
                  <strong>{activity.title}</strong>
                  {activity.category && <span>{activity.category}</span>}
                </div>
                <div className="activity-detail-meta">
                  {(activity.startTime || activity.endTime) && <span>{activity.startTime ?? "--"} - {activity.endTime ?? "--"}</span>}
                  {activity.location && <span>{activity.location}</span>}
                </div>
                {activity.description && <p>{activity.description}</p>}
              </article>
            ))}
          </div>
        ) : (
          <p className="empty-state compact-state">Tidak ada kegiatan di tanggal ini.</p>
        )}
      </div>
    </section>
  );
}
