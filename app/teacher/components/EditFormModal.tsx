"use client";

import { useState } from "react";

export function EditFormModal({
  title,
  buttonLabel = "Edit",
  children,
}: {
  title: string;
  buttonLabel?: string;
  children: React.ReactNode;
}) {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <>
      <button type="button" className="modal-trigger-button" onClick={() => setIsOpen(true)}>
        {buttonLabel}
      </button>

      {isOpen && (
        <div className="modal-backdrop" onClick={(event) => {
          if (event.target === event.currentTarget) setIsOpen(false);
        }}>
          <div className="modal-card" role="dialog" aria-modal="true" aria-label={title}>
            <div className="modal-header">
              <div>
                <p className="eyebrow">Edit data</p>
                <h3>{title}</h3>
              </div>
              <button type="button" className="modal-close" onClick={() => setIsOpen(false)} aria-label="Tutup modal">
                ×
              </button>
            </div>

            <div className="modal-content">
              {children}
            </div>
          </div>
        </div>
      )}
    </>
  );
}
