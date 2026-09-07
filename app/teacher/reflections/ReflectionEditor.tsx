"use client";

import { useEffect, useRef, useState } from "react";

type ReflectionEditorProps = {
  name?: string;
  initialValue?: string;
  label?: string;
};

export function ReflectionEditor({ name = "content", initialValue = "", label = "Konten renungan" }: ReflectionEditorProps) {
  const editorRef = useRef<HTMLDivElement>(null);
  const textareaRef = useRef<HTMLTextAreaElement>(null);
  const [isOpen, setIsOpen] = useState(false);

  const syncValue = () => {
    if (!textareaRef.current || !editorRef.current) return;
    textareaRef.current.value = editorRef.current.innerHTML;
  };

  const command = (action: string, value?: string) => {
    editorRef.current?.focus();
    document.execCommand(action, false, value);
    syncValue();
  };

  useEffect(() => {
    if (!editorRef.current) return;
    editorRef.current.innerHTML = initialValue;
    syncValue();
  }, [initialValue]);

  return (
    <div className="editor-popup">
      <button type="button" className="open-editor" onClick={() => setIsOpen(true)}>
        {initialValue ? "Edit konten renungan" : "Buka editor konten"}
      </button>

      {isOpen && (
        <div className="editor-modal-backdrop" onClick={(event) => {
          if (event.target === event.currentTarget) setIsOpen(false);
        }}>
          <div className="editor-modal-box" role="dialog" aria-modal="true" aria-label={label}>
            <div className="editor-header">
              <div>
                <p className="eyebrow">Editor</p>
                <h3>{label}</h3>
              </div>
              <button type="button" className="close-editor-inline" onClick={() => setIsOpen(false)} aria-label="Tutup editor">
                ×
              </button>
            </div>

            <div className="editor-tools">
              <button type="button" onClick={() => command("bold")}>B</button>
              <button type="button" onClick={() => command("italic")}><i>I</i></button>
              <button type="button" onClick={() => command("underline")}><u>U</u></button>
              <button type="button" onClick={() => command("insertUnorderedList")}>List</button>
              <button type="button" onClick={() => command("formatBlock", "blockquote")}>Quote</button>
            </div>

            <div
              ref={editorRef}
              className="rich-editor"
              contentEditable
              suppressContentEditableWarning
              aria-label={label}
              onInput={syncValue}
            />

            <div className="editor-actions">
              <button type="button" className="secondary-button" onClick={() => setIsOpen(false)}>
                Tutup
              </button>
              <button type="button" className="primary-button" onClick={() => setIsOpen(false)}>
                Simpan konten
              </button>
            </div>
          </div>
        </div>
      )}

      <textarea
        ref={textareaRef}
        name={name}
        className="editor-value"
        defaultValue={initialValue}
        required
      />
    </div>
  );
}

