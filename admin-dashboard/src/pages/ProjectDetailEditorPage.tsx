import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { api, getImageUrl, getPublicPageUrl } from '../api/client';
import type { DetailBlock, BlockType, GalleryItem, ApiResponse } from '../types';

// Helper to sanitize HTML preview client-side
const sanitizePreviewHtml = (raw: string): string => {
  return raw
    .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
    .replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi, '')
    .replace(/\s+on[a-z]+\s*=\s*("[^"]*"|'[^']*'|[^\s>]+)/gi, '')
    .replace(/(href|src)\s*=\s*["']\s*(javascript|vbscript|data):[^"']*["']/gi, '$1="#"');
};
import {
  ArrowLeft,
  Save,
  Eye,
  Plus,
  Trash2,
  ChevronUp,
  ChevronDown,
  Heading,
  AlignLeft,
  Image as ImageIcon,
  Images,
  MessageSquareQuote,
  Minus,
  Code,
  Upload,
  AlertCircle,
  CheckCircle2,
  Layers,
  Sparkles,
} from 'lucide-react';

interface ProjectContentData {
  id: number;
  title: string;
  slug: string;
  detail_mode: 'basic' | 'custom';
  detail_blocks: DetailBlock[];
}

export const ProjectDetailEditorPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();

  const [isLoading, setIsLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);
  const [projectTitle, setProjectTitle] = useState('');
  const [detailMode, setDetailMode] = useState<'basic' | 'custom'>('custom');
  const [blocks, setBlocks] = useState<DetailBlock[]>([]);
  const [isDirty, setIsDirty] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);
  const [previewHtmlId, setPreviewHtmlId] = useState<string | null>(null);

  // Load project content
  useEffect(() => {
    const fetchContent = async () => {
      if (!id) return;
      setIsLoading(true);
      try {
        const res = await api.get<ApiResponse<ProjectContentData>>(`/projects/detail_content.php?id=${id}`);
        if (res.data.success && res.data.data) {
          const data = res.data.data;
          setProjectTitle(data.title);
          setDetailMode(data.detail_mode || 'basic');
          setBlocks(Array.isArray(data.detail_blocks) ? data.detail_blocks : []);
          setIsDirty(false);
        } else {
          setFeedback({ type: 'error', message: res.data.message || 'Không thể tải nội dung dự án.' });
        }
      } catch (err: any) {
        setFeedback({
          type: 'error',
          message: err.response?.data?.message || 'Lỗi kết nối khi tải nội dung dự án.',
        });
      } finally {
        setIsLoading(false);
      }
    };

    fetchContent();
  }, [id]);

  const generateId = () => 'blk_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7);

  // Add block
  const handleAddBlock = (type: BlockType) => {
    let newBlock: DetailBlock;
    const newId = generateId();

    switch (type) {
      case 'heading':
        newBlock = { id: newId, type: 'heading', level: 2, text: '' };
        break;
      case 'paragraph':
        newBlock = { id: newId, type: 'paragraph', text: '' };
        break;
      case 'image':
        newBlock = { id: newId, type: 'image', src: '', alt: '', caption: '' };
        break;
      case 'gallery':
        newBlock = { id: newId, type: 'gallery', images: [] };
        break;
      case 'callout':
        newBlock = { id: newId, type: 'callout', title: '', text: '', variant: 'gold' };
        break;
      case 'divider':
        newBlock = { id: newId, type: 'divider' };
        break;
      case 'html':
        newBlock = { id: newId, type: 'html', content: '' };
        break;
    }

    setBlocks((prev) => [...prev, newBlock]);
    setDetailMode('custom'); // Automatically enable custom mode when adding blocks
    setIsDirty(true);
  };

  // Update block
  const handleUpdateBlock = (id: string, updates: Partial<DetailBlock>) => {
    setBlocks((prev) =>
      prev.map((b) => (b.id === id ? { ...b, ...updates } : b))
    );
    setIsDirty(true);
  };

  // Delete block
  const handleDeleteBlock = (id: string) => {
    setBlocks((prev) => prev.filter((b) => b.id !== id));
    setIsDirty(true);
  };

  // Move block
  const handleMoveBlock = (index: number, direction: 'up' | 'down') => {
    const targetIndex = direction === 'up' ? index - 1 : index + 1;
    if (targetIndex < 0 || targetIndex >= blocks.length) return;

    const newBlocks = [...blocks];
    const temp = newBlocks[index];
    newBlocks[index] = newBlocks[targetIndex];
    newBlocks[targetIndex] = temp;

    setBlocks(newBlocks);
    setIsDirty(true);
  };

  // Upload image helper
  const handleUploadFile = async (file: File): Promise<string> => {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('folder', 'projects/details');

    const res = await api.post('/upload.php', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    if (res.data.success && res.data.data?.filename) {
      return res.data.data.filename;
    }
    throw new Error(res.data.message || 'Lỗi tải ảnh lên máy chủ');
  };

  // Upload single image for Image Block
  const handleImageBlockUpload = async (blockId: string, e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files || !e.target.files[0]) return;
    const file = e.target.files[0];
    try {
      const filename = await handleUploadFile(file);
      handleUpdateBlock(blockId, { src: filename });
    } catch (err: any) {
      alert(err.message || 'Lỗi tải ảnh');
    }
  };

  // Add images to Gallery Block
  const handleGalleryUpload = async (blockId: string, currentImages: GalleryItem[], e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files || e.target.files.length === 0) return;
    const files = Array.from(e.target.files);

    try {
      const newItems: GalleryItem[] = [];
      for (const file of files) {
        const filename = await handleUploadFile(file);
        newItems.push({ src: filename, alt: '', caption: '' });
      }
      handleUpdateBlock(blockId, { images: [...currentImages, ...newItems] });
    } catch (err: any) {
      alert(err.message || 'Lỗi tải ảnh vào bộ sưu tập');
    }
  };

  // Save handler
  const handleSave = async () => {
    if (!id) return;
    setIsSaving(true);
    setFeedback(null);

    try {
      const res = await api.post('/projects/save_detail_content.php', {
        id: Number(id),
        detail_mode: detailMode,
        detail_blocks: blocks,
      });

      if (res.data.success) {
        setIsDirty(false);
        setFeedback({ type: 'success', message: 'Nội dung chi tiết dự án đã được lưu thành công!' });
      } else {
        setFeedback({ type: 'error', message: res.data.message || 'Có lỗi xảy ra khi lưu.' });
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể lưu nội dung dự án.',
      });
    } finally {
      setIsSaving(false);
    }
  };

  // Preview handler
  const handlePreview = () => {
    if (isDirty) {
      const proceed = window.confirm(
        'Bạn có thay đổi chưa lưu. Hãy lưu lại trước khi xem trước để hiển thị dữ liệu mới nhất. Bạn có muốn lưu ngay bây giờ?'
      );
      if (proceed) {
        handleSave().then(() => {
          window.open(getPublicPageUrl(`/project-detail.php?id=${id}`), '_blank');
        });
        return;
      }
    }
    window.open(getPublicPageUrl(`/project-detail.php?id=${id}`), '_blank');
  };

  if (isLoading) {
    return (
      <div className="min-h-[500px] flex items-center justify-center">
        <div className="flex flex-col items-center gap-3">
          <div className="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
          <p className="text-sm text-slate-500">Đang tải trình soạn thảo chi tiết dự án...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-6 max-w-5xl mx-auto pb-24">
      {/* Top Bar Header */}
      <div className="sticky top-0 z-30 bg-slate-50/95 dark:bg-navy-950/95 backdrop-blur-md py-4 border-b border-slate-200 dark:border-navy-800 -mx-4 sm:-mx-6 px-4 sm:px-6">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex items-center gap-3">
            <button
              onClick={() => navigate('/projects')}
              className="p-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-white dark:bg-navy-900 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-navy-800 transition"
              title="Quay lại danh sách công trình"
            >
              <ArrowLeft className="w-4 h-4" />
            </button>
            <div>
              <div className="flex items-center gap-2">
                <h1 className="text-lg font-bold text-slate-900 dark:text-white line-clamp-1 max-w-md">
                  {projectTitle || 'Soạn trang chi tiết'}
                </h1>
                <span
                  className={`px-2 py-0.5 rounded-md text-xs font-semibold ${
                    detailMode === 'custom'
                      ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border border-amber-300 dark:border-amber-800'
                      : 'bg-slate-100 text-slate-600 dark:bg-navy-800 dark:text-slate-400 border border-slate-200 dark:border-navy-700'
                  }`}
                >
                  {detailMode === 'custom' ? 'Nội dung Nâng cao' : 'Nội dung Cơ bản'}
                </span>
              </div>
              <div className="flex items-center gap-2 mt-0.5 text-xs text-slate-400">
                {isDirty ? (
                  <span className="flex items-center gap-1 text-amber-600 dark:text-amber-400 font-medium">
                    <span className="w-2 h-2 rounded-full bg-amber-500 animate-pulse" /> Có thay đổi chưa lưu
                  </span>
                ) : (
                  <span className="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
                    <CheckCircle2 className="w-3.5 h-3.5" /> Đã lưu đồng bộ
                  </span>
                )}
                <span>•</span>
                <span>{blocks.length} khối nội dung</span>
              </div>
            </div>
          </div>

          <div className="flex items-center gap-2.5 flex-wrap">
            {/* Mode selector */}
            <div className="flex items-center bg-white dark:bg-navy-900 border border-slate-200 dark:border-navy-700 rounded-xl p-1 text-xs">
              <button
                type="button"
                onClick={() => {
                  setDetailMode('basic');
                  setIsDirty(true);
                }}
                className={`px-2.5 py-1.5 rounded-lg font-semibold transition ${
                  detailMode === 'basic'
                    ? 'bg-navy-800 text-white dark:bg-white dark:text-navy-950 shadow-sm'
                    : 'text-slate-600 dark:text-slate-400'
                }`}
              >
                Cơ bản
              </button>
              <button
                type="button"
                onClick={() => {
                  setDetailMode('custom');
                  setIsDirty(true);
                }}
                className={`px-2.5 py-1.5 rounded-lg font-semibold transition ${
                  detailMode === 'custom'
                    ? 'bg-amber-500 text-navy-950 shadow-sm'
                    : 'text-slate-600 dark:text-slate-400'
                }`}
              >
                Nâng cao
              </button>
            </div>

            {/* Preview button */}
            <button
              type="button"
              onClick={handlePreview}
              className="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-navy-700 bg-white dark:bg-navy-900 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-navy-800 font-semibold text-xs flex items-center gap-1.5 shadow-sm transition"
              title="Xem giao diện người dùng trên website"
            >
              <Eye className="w-3.5 h-3.5 text-blue-500" />
              <span>Xem trước</span>
            </button>

            {/* Save button */}
            <button
              type="button"
              onClick={handleSave}
              disabled={isSaving}
              className="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-navy-950 font-bold text-xs flex items-center gap-1.5 shadow-md shadow-amber-500/20 transition"
            >
              <Save className="w-3.5 h-3.5" />
              <span>{isSaving ? 'Đang lưu...' : 'Lưu nội dung'}</span>
            </button>
          </div>
        </div>
      </div>

      {/* Feedback Toast Banner */}
      {feedback && (
        <div
          className={`p-4 rounded-xl flex items-center justify-between gap-3 text-sm ${
            feedback.type === 'success'
              ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
              : 'bg-rose-50 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800'
          }`}
        >
          <div className="flex items-center gap-2">
            {feedback.type === 'success' ? (
              <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0" />
            ) : (
              <AlertCircle className="w-4 h-4 text-rose-600 flex-shrink-0" />
            )}
            <span>{feedback.message}</span>
          </div>
          <button
            onClick={() => setFeedback(null)}
            className="text-xs font-bold hover:underline opacity-70 hover:opacity-100"
          >
            Đóng
          </button>
        </div>
      )}

      {/* Mode hint notice if basic */}
      {detailMode === 'basic' && (
        <div className="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 text-blue-800 dark:text-blue-300 text-xs flex items-start gap-3">
          <AlertCircle className="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" />
          <div>
            <p className="font-bold">Đang ở chế độ hiển thị cơ bản</p>
            <p className="mt-0.5 leading-relaxed opacity-90">
              Trang chi tiết dự án công khai đang sử dụng giao diện mặc định (Mô tả tóm tắt + Nội dung đơn giản). Hãy chọn chế độ "Nâng cao" ở góc trên để kích hoạt các khối nội dung được soạn bên dưới.
            </p>
          </div>
        </div>
      )}

      {/* Block Palette Toolbar */}
      <div className="bg-white dark:bg-navy-900 p-4 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm">
        <div className="flex items-center gap-2 mb-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
          <Sparkles className="w-3.5 h-3.5 text-amber-500" />
          <span>Thêm khối nội dung mới:</span>
        </div>
        <div className="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
          <button
            type="button"
            onClick={() => handleAddBlock('heading')}
            className="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <Heading className="w-4 h-4 text-amber-500" />
            <span>Tiêu đề</span>
          </button>

          <button
            type="button"
            onClick={() => handleAddBlock('paragraph')}
            className="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <AlignLeft className="w-4 h-4 text-blue-500" />
            <span>Đoạn văn</span>
          </button>

          <button
            type="button"
            onClick={() => handleAddBlock('image')}
            className="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <ImageIcon className="w-4 h-4 text-emerald-500" />
            <span>Hình ảnh</span>
          </button>

          <button
            type="button"
            onClick={() => handleAddBlock('gallery')}
            className="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <Images className="w-4 h-4 text-purple-500" />
            <span>Bộ sưu tập ảnh</span>
          </button>

          <button
            type="button"
            onClick={() => handleAddBlock('callout')}
            className="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <MessageSquareQuote className="w-4 h-4 text-orange-500" />
            <span>Ghi chú nổi bật</span>
          </button>

          <button
            type="button"
            onClick={() => handleAddBlock('divider')}
            className="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <Minus className="w-4 h-4 text-slate-400" />
            <span>Phân cách</span>
          </button>

          <button
            type="button"
            onClick={() => handleAddBlock('html')}
            className="col-span-2 sm:col-span-1 px-3 py-2.5 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center justify-center sm:justify-start gap-2 transition min-h-[40px]"
          >
            <Code className="w-4 h-4 text-rose-500" />
            <span>HTML tùy chỉnh</span>
          </button>
        </div>
      </div>

      {/* Blocks List Canvas */}
      <div className="space-y-4">
        {blocks.length === 0 ? (
          <div className="text-center py-16 px-4 bg-white dark:bg-navy-900 rounded-3xl border border-dashed border-slate-300 dark:border-navy-800">
            <div className="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-500 flex items-center justify-center mx-auto mb-4">
              <Layers className="w-8 h-8" />
            </div>
            <h3 className="text-base font-bold text-slate-800 dark:text-white mb-1">
              Chưa có khối nội dung nào
            </h3>
            <p className="text-xs text-slate-400 max-w-md mx-auto mb-6">
              Bắt đầu xây dựng trang chi tiết dự án chuyên nghiệp bằng cách bấm chọn một trong các khối nội dung phía trên.
            </p>
            <button
              type="button"
              onClick={() => handleAddBlock('heading')}
              className="px-4 py-2 rounded-xl bg-navy-800 text-white dark:bg-white dark:text-navy-950 font-bold text-xs inline-flex items-center gap-2 shadow-sm"
            >
              <Plus className="w-4 h-4" />
              <span>Thêm Tiêu đề đầu tiên</span>
            </button>
          </div>
        ) : (
          blocks.map((block, index) => (
            <div
              key={block.id}
              className="bg-white dark:bg-navy-900 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm transition-all hover:border-slate-300 dark:hover:border-navy-700 overflow-hidden"
            >
              {/* Block Header */}
              <div className="bg-slate-50 dark:bg-navy-950/70 px-4 py-2.5 border-b border-slate-200 dark:border-navy-800 flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <span className="w-5 h-5 rounded-md bg-slate-200 dark:bg-navy-800 text-slate-600 dark:text-slate-400 flex items-center justify-center text-xs font-bold">
                    {index + 1}
                  </span>
                  <div className="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                    {block.type === 'heading' && <Heading className="w-3.5 h-3.5 text-amber-500" />}
                    {block.type === 'paragraph' && <AlignLeft className="w-3.5 h-3.5 text-blue-500" />}
                    {block.type === 'image' && <ImageIcon className="w-3.5 h-3.5 text-emerald-500" />}
                    {block.type === 'gallery' && <Images className="w-3.5 h-3.5 text-purple-500" />}
                    {block.type === 'callout' && <MessageSquareQuote className="w-3.5 h-3.5 text-orange-500" />}
                    {block.type === 'divider' && <Minus className="w-3.5 h-3.5 text-slate-400" />}
                    {block.type === 'html' && <Code className="w-3.5 h-3.5 text-rose-500" />}
                    <span className="uppercase tracking-wider">
                      {block.type === 'heading' && `Tiêu đề (H${block.level || 2})`}
                      {block.type === 'paragraph' && 'Đoạn văn'}
                      {block.type === 'image' && 'Hình ảnh đơn'}
                      {block.type === 'gallery' && `Bộ sưu tập (${block.images?.length || 0} ảnh)`}
                      {block.type === 'callout' && 'Ghi chú nổi bật'}
                      {block.type === 'divider' && 'Đường phân cách'}
                      {block.type === 'html' && 'Khối HTML tùy chỉnh'}
                    </span>
                  </div>
                </div>

                <div className="flex items-center gap-1">
                  <button
                    type="button"
                    onClick={() => handleMoveBlock(index, 'up')}
                    disabled={index === 0}
                    className="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 disabled:opacity-30 disabled:hover:text-slate-400"
                    title="Di chuyển lên"
                  >
                    <ChevronUp className="w-4 h-4" />
                  </button>
                  <button
                    type="button"
                    onClick={() => handleMoveBlock(index, 'down')}
                    disabled={index === blocks.length - 1}
                    className="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 disabled:opacity-30 disabled:hover:text-slate-400"
                    title="Di chuyển xuống"
                  >
                    <ChevronDown className="w-4 h-4" />
                  </button>
                  <button
                    type="button"
                    onClick={() => handleDeleteBlock(block.id)}
                    className="p-1 rounded-md text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 ml-1"
                    title="Xóa khối"
                  >
                    <Trash2 className="w-4 h-4" />
                  </button>
                </div>
              </div>

              {/* Block Content Body */}
              <div className="p-4">
                {/* 1. HEADING */}
                {block.type === 'heading' && (
                  <div className="space-y-3">
                    <div className="flex items-center gap-2">
                      <span className="text-xs text-slate-400 font-semibold">Cấp độ:</span>
                      {[2, 3, 4].map((lvl) => (
                        <button
                          key={lvl}
                          type="button"
                          onClick={() => handleUpdateBlock(block.id, { level: lvl as 2 | 3 | 4 })}
                          className={`px-2.5 py-1 rounded-md text-xs font-bold transition ${
                            (block.level || 2) === lvl
                              ? 'bg-amber-500 text-navy-950 shadow-sm'
                              : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400'
                          }`}
                        >
                          H{lvl}
                        </button>
                      ))}
                    </div>
                    <input
                      type="text"
                      value={block.text || ''}
                      onChange={(e) => handleUpdateBlock(block.id, { text: e.target.value })}
                      placeholder="Nhập nội dung tiêu đề..."
                      className="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white font-bold text-base focus:ring-2 focus:ring-amber-500 outline-none"
                    />
                  </div>
                )}

                {/* 2. PARAGRAPH */}
                {block.type === 'paragraph' && (
                  <textarea
                    rows={4}
                    value={block.text || ''}
                    onChange={(e) => handleUpdateBlock(block.id, { text: e.target.value })}
                    placeholder="Nhập nội dung văn bản (hỗ trợ xuống dòng tự nhiên)..."
                    className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white text-sm leading-relaxed focus:ring-2 focus:ring-amber-500 outline-none"
                  />
                )}

                {/* 3. IMAGE */}
                {block.type === 'image' && (
                  <div className="space-y-3">
                    {block.src && (
                      <div className="relative rounded-xl overflow-hidden border border-slate-200 dark:border-navy-700 max-h-72 bg-slate-100 dark:bg-navy-950">
                        <img
                          src={getImageUrl(block.src)}
                          alt={block.alt || 'Preview'}
                          className="w-full h-full object-contain max-h-72 mx-auto"
                        />
                      </div>
                    )}
                    <div className="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                      <label className="flex-1 flex items-center justify-center gap-2 px-3.5 py-2.5 border border-dashed border-slate-300 dark:border-navy-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-navy-800 text-xs font-semibold text-slate-700 dark:text-slate-300 transition">
                        <Upload className="w-4 h-4 text-emerald-500" />
                        <span>{block.src ? 'Thay đổi ảnh từ máy tính' : 'Tải ảnh lên từ máy tính'}</span>
                        <input
                          type="file"
                          accept="image/*"
                          onChange={(e) => handleImageBlockUpload(block.id, e)}
                          className="hidden"
                        />
                      </label>
                      <input
                        type="text"
                        value={block.src || ''}
                        onChange={(e) => handleUpdateBlock(block.id, { src: e.target.value })}
                        placeholder="Hoặc dán URL ảnh / tên file..."
                        className="flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                      <input
                        type="text"
                        value={block.alt || ''}
                        onChange={(e) => handleUpdateBlock(block.id, { alt: e.target.value })}
                        placeholder="Mô tả ảnh (Alt text)..."
                        className="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500"
                      />
                      <input
                        type="text"
                        value={block.caption || ''}
                        onChange={(e) => handleUpdateBlock(block.id, { caption: e.target.value })}
                        placeholder="Chú thích dưới ảnh (Caption)..."
                        className="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500"
                      />
                    </div>
                  </div>
                )}

                {/* 4. GALLERY */}
                {block.type === 'gallery' && (
                  <div className="space-y-4">
                    <div className="flex items-center justify-between">
                      <span className="text-xs text-slate-500">
                        Hỗ trợ nhiều hình ảnh hiển thị dạng lưới Responsive 3 cột (Desktop).
                      </span>
                      <label className="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/60 dark:hover:bg-purple-900 text-purple-700 dark:text-purple-300 font-bold text-xs flex items-center gap-1.5 cursor-pointer transition">
                        <Upload className="w-3.5 h-3.5" />
                        <span>+ Thêm ảnh</span>
                        <input
                          type="file"
                          accept="image/*"
                          multiple
                          onChange={(e) => handleGalleryUpload(block.id, block.images || [], e)}
                          className="hidden"
                        />
                      </label>
                    </div>

                    {(block.images || []).length === 0 ? (
                      <div className="text-center py-8 border border-dashed border-slate-200 dark:border-navy-800 rounded-xl text-xs text-slate-400">
                        Chưa có ảnh nào trong bộ sưu tập. Hãy bấm "+ Thêm ảnh" để tải nhiều ảnh cùng lúc.
                      </div>
                    ) : (
                      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        {(block.images || []).map((img, imgIdx) => (
                          <div
                            key={imgIdx}
                            className="p-2 rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 flex flex-col gap-2"
                          >
                            <div className="relative aspect-video rounded-lg overflow-hidden bg-slate-200 dark:bg-navy-900">
                              <img
                                src={getImageUrl(img.src)}
                                alt={img.alt || 'Gallery photo'}
                                className="w-full h-full object-cover"
                              />
                              <button
                                type="button"
                                onClick={() => {
                                  const updated = (block.images || []).filter((_, i) => i !== imgIdx);
                                  handleUpdateBlock(block.id, { images: updated });
                                }}
                                className="absolute top-1.5 right-1.5 p-1 rounded-md bg-rose-600 text-white hover:bg-rose-700 transition shadow"
                                title="Xóa ảnh"
                              >
                                <Trash2 className="w-3 h-3" />
                              </button>
                            </div>
                            <input
                              type="text"
                              value={img.caption || ''}
                              onChange={(e) => {
                                const newImages = [...(block.images || [])];
                                newImages[imgIdx] = { ...newImages[imgIdx], caption: e.target.value };
                                handleUpdateBlock(block.id, { images: newImages });
                              }}
                              placeholder="Chú thích ảnh..."
                              className="w-full px-2 py-1 text-xs rounded-lg border border-slate-200 dark:border-navy-700 bg-white dark:bg-navy-900 text-slate-800 dark:text-slate-200 outline-none"
                            />
                          </div>
                        ))}
                      </div>
                    )}
                  </div>
                )}

                {/* 5. CALLOUT */}
                {block.type === 'callout' && (
                  <div className="space-y-3">
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="text-xs text-slate-400 font-semibold">Phong cách:</span>
                      {[
                        { key: 'gold', label: 'Vàng PNMEC', class: 'bg-amber-100 text-amber-800 border-amber-300' },
                        { key: 'info', label: 'Thông tin (Xanh dương)', class: 'bg-blue-100 text-blue-800 border-blue-300' },
                        { key: 'warning', label: 'Cảnh báo (Cam)', class: 'bg-orange-100 text-orange-800 border-orange-300' },
                        { key: 'success', label: 'Thành công (Xanh lá)', class: 'bg-emerald-100 text-emerald-800 border-emerald-300' },
                      ].map((item) => (
                        <button
                          key={item.key}
                          type="button"
                          onClick={() => handleUpdateBlock(block.id, { variant: item.key as any })}
                          className={`px-2.5 py-1 rounded-md text-xs font-semibold border transition ${
                            (block.variant || 'gold') === item.key
                              ? `${item.class} shadow-sm font-bold`
                              : 'bg-slate-50 dark:bg-navy-800 text-slate-600 dark:text-slate-400 border-transparent'
                          }`}
                        >
                          {item.label}
                        </button>
                      ))}
                    </div>
                    <input
                      type="text"
                      value={block.title || ''}
                      onChange={(e) => handleUpdateBlock(block.id, { title: e.target.value })}
                      placeholder="Tiêu đề ghi chú (VD: Khối lượng thép: 120 tấn / Thông số kỹ thuật)..."
                      className="w-full px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500"
                    />
                    <textarea
                      rows={2}
                      value={block.text || ''}
                      onChange={(e) => handleUpdateBlock(block.id, { text: e.target.value })}
                      placeholder="Chi tiết ghi chú kỹ thuật hoặc điểm nhấn dự án..."
                      className="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500 leading-relaxed"
                    />
                  </div>
                )}

                {/* 6. DIVIDER */}
                {block.type === 'divider' && (
                  <div className="py-2">
                    <div className="border-t border-slate-300 dark:border-navy-700 my-1" />
                    <p className="text-center text-[10px] uppercase tracking-widest text-slate-400 mt-1">
                      Đường phân cách section trên website
                    </p>
                  </div>
                )}

                {/* 7. HTML CODE BLOCK */}
                {block.type === 'html' && (
                  <div className="space-y-2">
                    <div className="flex items-center justify-between">
                      <span className="text-xs text-slate-400 font-mono">
                        Hỗ trợ thẻ HTML chuẩn (section, div, p, table...). Tự động loại bỏ script độc hại.
                      </span>
                      <button
                        type="button"
                        onClick={() =>
                          setPreviewHtmlId(previewHtmlId === block.id ? null : block.id)
                        }
                        className="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1"
                      >
                        <Eye className="w-3.5 h-3.5" />
                        <span>{previewHtmlId === block.id ? 'Ẩn xem thử' : 'Xem thử kết quả'}</span>
                      </button>
                    </div>

                    <textarea
                      rows={6}
                      value={block.content || ''}
                      onChange={(e) => handleUpdateBlock(block.id, { content: e.target.value })}
                      placeholder="<section class='my-custom-section'>&#10;  <h3>Bảng Thông Số Kỹ Thuật</h3>&#10;  ...&#10;</section>"
                      className="w-full font-mono text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-slate-900 text-emerald-400 leading-relaxed outline-none focus:ring-2 focus:ring-amber-500"
                    />

                    {previewHtmlId === block.id && (
                      <div className="mt-2 p-3 bg-white dark:bg-navy-950 border border-slate-200 dark:border-navy-700 rounded-xl">
                        <div className="text-[10px] uppercase font-bold text-slate-400 mb-2 border-b pb-1">
                          Kết quả hiển thị mẫu:
                        </div>
                        <div
                          className="prose dark:prose-invert max-w-none text-xs"
                          dangerouslySetInnerHTML={{ __html: sanitizePreviewHtml(block.content || '') }}
                        />
                      </div>
                    )}
                  </div>
                )}
              </div>
            </div>
          ))
        )}
      </div>

      {/* Floating Bottom Add Bar if blocks exist */}
      {blocks.length > 0 && (
        <div className="flex justify-center pt-4">
          <button
            type="button"
            onClick={handleSave}
            disabled={isSaving}
            className="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-navy-950 font-bold text-sm flex items-center gap-2 shadow-lg shadow-amber-500/25 transition"
          >
            <Save className="w-4 h-4" />
            <span>{isSaving ? 'Đang lưu nội dung...' : 'Lưu tất cả thay đổi'}</span>
          </button>
        </div>
      )}
    </div>
  );
};
export default ProjectDetailEditorPage;
