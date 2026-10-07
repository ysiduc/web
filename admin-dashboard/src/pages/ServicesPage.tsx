import React, { useEffect, useState } from 'react';
import { api, getImageUrl, getPublicPageUrl } from '../api/client';
import type { Service, ApiResponse } from '../types';
import { Modal } from '../components/common/Modal';
import { ConfirmModal } from '../components/common/ConfirmModal';
import {
  Plus,
  Search,
  Edit2,
  Trash2,
  Star,
  ExternalLink,
  Upload,
} from 'lucide-react';

export const ServicesPage: React.FC = () => {
  const [services, setServices] = useState<Service[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [sector, setSector] = useState('');
  const [featuredOnly, setFeaturedOnly] = useState(false);

  // Modal states
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingService, setEditingService] = useState<Service | null>(null);
  const [deleteTarget, setDeleteTarget] = useState<Service | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  // Form states
  const [formTitle, setFormTitle] = useState('');
  const [formCode, setFormCode] = useState('');
  const [formSummary, setFormSummary] = useState('');
  const [formContent, setFormContent] = useState('');
  const [formFeatured, setFormFeatured] = useState(false);
  const [formStatus, setFormStatus] = useState<'active' | 'inactive'>('active');
  const [formImageFile, setFormImageFile] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState<string>('');

  const fetchServices = async () => {
    setIsLoading(true);
    try {
      const res = await api.get<ApiResponse<{ services: Service[] }>>('/services/index.php', {
        params: {
          search,
          sector,
          featured: featuredOnly ? 1 : undefined,
        },
      });
      if (res.data.success) {
        setServices(res.data.data.services);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchServices();
  }, [search, sector, featuredOnly]);

  const handleOpenCreate = () => {
    setEditingService(null);
    setFormTitle('');
    setFormCode('CK-');
    setFormSummary('');
    setFormContent('');
    setFormFeatured(false);
    setFormStatus('active');
    setFormImageFile(null);
    setImagePreview('');
    setIsModalOpen(true);
  };

  const handleOpenEdit = (s: Service) => {
    setEditingService(s);
    setFormTitle(s.title);
    setFormCode(s.code || '');
    setFormSummary(s.summary || '');
    setFormContent(s.content || '');
    setFormFeatured(Boolean(s.featured));
    setFormStatus(s.status || 'active');
    setFormImageFile(null);
    setImagePreview(getImageUrl(s.image));
    setIsModalOpen(true);
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      setFormImageFile(file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const handleSubmitForm = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formTitle.trim()) return;

    setIsSubmitting(true);
    setFeedback(null);

    const formData = new FormData();
    formData.append('title', formTitle.trim());
    formData.append('code', formCode.trim());
    formData.append('summary', formSummary.trim());
    formData.append('content', formContent.trim());
    formData.append('featured', formFeatured ? '1' : '0');
    formData.append('status', formStatus);

    if (formImageFile) {
      formData.append('image', formImageFile);
    }

    try {
      if (editingService) {
        formData.append('id', String(editingService.id));
        const res = await api.post('/services/update.php', formData);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Cập nhật dịch vụ thành công!' });
          setIsModalOpen(false);
          fetchServices();
        }
      } else {
        const res = await api.post('/services/create.php', formData);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Tạo dịch vụ mới thành công!' });
          setIsModalOpen(false);
          fetchServices();
        }
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Có lỗi xảy ra khi lưu dịch vụ.',
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleToggleFeatured = async (s: Service) => {
    try {
      const newFeatured = !s.featured;
      const formData = new FormData();
      formData.append('id', String(s.id));
      formData.append('featured', newFeatured ? '1' : '0');
      const res = await api.post('/services/update.php', formData);
      if (res.data.success) {
        setServices((prev) =>
          prev.map((item) =>
            item.id === s.id ? { ...item, featured: newFeatured ? 1 : 0 } : item
          )
        );
        setFeedback({
          type: 'success',
          message: newFeatured
            ? `Đã bật nổi bật cho dịch vụ "${s.title}"!`
            : `Đã tắt nổi bật cho dịch vụ "${s.title}"!`,
        });
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Có lỗi xảy ra khi đổi trạng thái nổi bật.',
      });
    }
  };

  const handleDelete = async () => {
    if (!deleteTarget) return;
    setIsSubmitting(true);
    try {
      const res = await api.post('/services/delete.php', { id: deleteTarget.id });
      if (res.data.success) {
        setFeedback({ type: 'success', message: 'Đã xóa dịch vụ thành công!' });
        setDeleteTarget(null);
        fetchServices();
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể xóa dịch vụ.',
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="space-y-6">
      {/* Title */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold text-slate-900 dark:text-white">
            Quản Lý Dịch Vụ Thi Công
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">
            12 Phân loại dịch vụ cốt lõi: Kết cấu thép, gia công cơ khí &amp; xây dựng công nghiệp
          </p>
        </div>
        <button
          onClick={handleOpenCreate}
          className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition self-start sm:self-auto"
        >
          <Plus className="w-4 h-4" />
          <span>Thêm Dịch Vụ Mới</span>
        </button>
      </div>

      {/* Feedback Toast */}
      {feedback && (
        <div
          className={`p-4 rounded-xl text-sm font-medium flex items-center justify-between ${
            feedback.type === 'success'
              ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
              : 'bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800'
          }`}
        >
          <span>{feedback.message}</span>
          <button
            onClick={() => setFeedback(null)}
            className="text-xs uppercase font-bold underline ml-4"
          >
            Đóng
          </button>
        </div>
      )}

      {/* Filter and Search Bar */}
      <div className="bg-white dark:bg-navy-900 p-4 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
        <div className="relative flex-1">
          <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Tìm theo tên dịch vụ hoặc mã (CK, XD)..."
            className="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <div className="flex flex-wrap items-center gap-2">
          {/* Sector tabs */}
          <button
            onClick={() => setSector('')}
            className={`px-3 py-1.5 rounded-xl text-xs font-semibold transition ${
              sector === ''
                ? 'bg-navy-800 text-white dark:bg-white dark:text-navy-950 shadow-sm'
                : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'
            }`}
          >
            Tất cả
          </button>
          <button
            onClick={() => setSector('co_khi')}
            className={`px-3 py-1.5 rounded-xl text-xs font-semibold transition ${
              sector === 'co_khi'
                ? 'bg-amber-500 text-navy-950 font-bold shadow-sm'
                : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'
            }`}
          >
            Cơ Khí (CK)
          </button>
          <button
            onClick={() => setSector('xay_dung')}
            className={`px-3 py-1.5 rounded-xl text-xs font-semibold transition ${
              sector === 'xay_dung'
                ? 'bg-blue-600 text-white font-bold shadow-sm'
                : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'
            }`}
          >
            Xây Dựng (XD)
          </button>

          <button
            onClick={() => setFeaturedOnly(!featuredOnly)}
            className={`px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 border transition ${
              featuredOnly
                ? 'bg-amber-50 text-amber-800 border-amber-300 dark:bg-amber-950/60 dark:text-amber-400 dark:border-amber-800'
                : 'border-slate-200 dark:border-navy-700 text-slate-600 dark:text-slate-400'
            }`}
          >
            <Star className={`w-3.5 h-3.5 ${featuredOnly ? 'fill-amber-500 text-amber-500' : ''}`} />
            <span>Nổi bật</span>
          </button>
        </div>
      </div>

      {/* Services Grid/Table */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm overflow-hidden">
        {isLoading ? (
          <div className="p-12 text-center">
            <div className="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
            <div className="mt-2 text-sm text-slate-400">Đang tải danh sách dịch vụ...</div>
          </div>
        ) : services.length === 0 ? (
          <div className="p-12 text-center text-slate-400 text-sm">
            Không tìm thấy dịch vụ nào.
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
              <thead className="bg-slate-50 dark:bg-navy-950 text-xs uppercase font-bold text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-navy-800">
                <tr>
                  <th className="px-6 py-4">Mã</th>
                  <th className="px-6 py-4">Tên dịch vụ</th>
                  <th className="px-4 py-4">Tóm tắt giải pháp</th>
                  <th className="px-4 py-4">Nổi bật</th>
                  <th className="px-6 py-4 text-right">Thao tác</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 dark:divide-navy-800">
                {services.map((s) => (
                  <tr
                    key={s.id}
                    className="hover:bg-slate-50/80 dark:hover:bg-navy-800/40 transition"
                  >
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className="px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                        {s.code || `ID-${s.id}`}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <div className="w-12 h-12 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0 border border-slate-200 dark:border-navy-700">
                          <img
                            src={getImageUrl(s.image)}
                            alt={s.title}
                            className="w-full h-full object-cover"
                            onError={(e) => {
                              (e.target as HTMLElement).style.display = 'none';
                            }}
                          />
                        </div>
                        <div>
                          <div className="font-bold text-slate-900 dark:text-white">
                            {s.title}
                          </div>
                          <div className="text-xs text-slate-400">
                            {s.views.toLocaleString()} lượt xem
                          </div>
                        </div>
                      </div>
                    </td>
                    <td className="px-4 py-4 text-xs max-w-sm">
                      <p className="line-clamp-2 text-slate-600 dark:text-slate-300">
                        {s.summary || 'Chưa cập nhật tóm tắt.'}
                      </p>
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap">
                      <button
                        type="button"
                        onClick={() => handleToggleFeatured(s)}
                        className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition shadow-sm ${
                          s.featured
                            ? 'bg-amber-100 text-amber-900 border border-amber-300 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-700 hover:bg-amber-200'
                            : 'bg-slate-100 text-slate-500 border border-slate-200 dark:bg-navy-800 dark:text-slate-400 dark:border-navy-700 hover:bg-slate-200'
                        }`}
                        title="Bấm để bật/tắt hiển thị Dịch vụ nổi bật trên Trang Chủ"
                      >
                        <Star className={`w-3.5 h-3.5 ${s.featured ? 'fill-amber-500 text-amber-500' : 'text-slate-400'}`} />
                        <span>{s.featured ? 'Nổi bật (Home)' : 'Thường'}</span>
                      </button>
                    </td>
                    <td className="px-6 py-4 text-right whitespace-nowrap">
                      <div className="flex items-center justify-end gap-1.5">
                        <a
                          href={getPublicPageUrl(`/service-detail.php?id=${s.id}`)}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
                          title="Xem trên website"
                        >
                          <ExternalLink className="w-4 h-4" />
                        </a>
                        <button
                          onClick={() => handleOpenEdit(s)}
                          className="p-2 text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition"
                          title="Sửa dịch vụ"
                        >
                          <Edit2 className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => setDeleteTarget(s)}
                          className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition"
                          title="Xóa dịch vụ"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Create / Edit Service Modal */}
      <Modal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        title={editingService ? 'Chỉnh Sửa Dịch Vụ Thi Công' : 'Thêm Dịch Vụ Mới'}
        maxWidth="2xl"
      >
        <form onSubmit={handleSubmitForm} className="space-y-4">
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div className="sm:col-span-2">
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Tên Dịch Vụ *
              </label>
              <input
                type="text"
                required
                value={formTitle}
                onChange={(e) => setFormTitle(e.target.value)}
                placeholder="VD: Gia Công &amp; Dựng Nhà Kết Cấu Thép"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Mã Dịch Vụ (Code)
              </label>
              <input
                type="text"
                value={formCode}
                onChange={(e) => setFormCode(e.target.value)}
                placeholder="VD: CK-01 hoặc XD-02"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm uppercase"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
            {/* Featured toggle */}
            <div className="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-navy-800 bg-slate-50 dark:bg-navy-950">
              <input
                type="checkbox"
                id="featured"
                checked={formFeatured}
                onChange={(e) => setFormFeatured(e.target.checked)}
                className="w-4 h-4 rounded text-amber-500 focus:ring-amber-500"
              />
              <label htmlFor="featured" className="text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                Đánh dấu là Dịch vụ nổi bật (Trang chủ)
              </label>
            </div>

            {/* Image Upload */}
            <div>
              <div className="flex items-center gap-3">
                {imagePreview && (
                  <div className="w-12 h-12 rounded-lg bg-slate-100 dark:bg-navy-800 overflow-hidden border border-slate-200 dark:border-navy-700 flex-shrink-0">
                    <img src={imagePreview} alt="Preview" className="w-full h-full object-cover" />
                  </div>
                )}
                <label className="flex-1 flex items-center justify-center gap-2 px-3 py-2 border border-dashed border-slate-300 dark:border-navy-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-navy-800 text-xs text-slate-600 dark:text-slate-300 transition">
                  <Upload className="w-4 h-4 text-amber-500" />
                  <span>Chọn ảnh minh họa</span>
                  <input
                    type="file"
                    accept="image/*"
                    onChange={handleImageChange}
                    className="hidden"
                  />
                </label>
              </div>
            </div>
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Tóm tắt giải pháp kỹ thuật
            </label>
            <textarea
              rows={2}
              value={formSummary}
              onChange={(e) => setFormSummary(e.target.value)}
              placeholder="Tóm tắt ngắn gọn giải pháp, máy móc CNC, quy trình thi công..."
              className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
            />
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Nội dung mô tả chi tiết
            </label>
            <textarea
              rows={5}
              value={formContent}
              onChange={(e) => setFormContent(e.target.value)}
              placeholder="Quy chuẩn thiết kế, tiêu chuẩn vật liệu thép, phương pháp hàn CO2/TIG..."
              className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm font-mono text-xs"
            />
          </div>

          <div className="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-navy-800">
            <button
              type="button"
              onClick={() => setIsModalOpen(false)}
              className="px-4 py-2 text-sm font-medium rounded-xl border border-slate-300 dark:border-navy-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-navy-800 transition"
            >
              Hủy bỏ
            </button>
            <button
              type="submit"
              disabled={isSubmitting}
              className="px-5 py-2 text-sm font-bold rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 shadow-md shadow-amber-500/20 transition disabled:opacity-50"
            >
              {isSubmitting ? 'Đang lưu...' : editingService ? 'Lưu Dịch Vụ' : 'Tạo Dịch Vụ'}
            </button>
          </div>
        </form>
      </Modal>

      {/* Delete Confirmation */}
      <ConfirmModal
        isOpen={Boolean(deleteTarget)}
        onClose={() => setDeleteTarget(null)}
        onConfirm={handleDelete}
        title="Xóa Dịch Vụ"
        message={`Bạn có chắc chắn muốn xóa dịch vụ "${deleteTarget?.title}"?`}
        confirmText="Xác nhận xóa"
        isDangerous
        isLoading={isSubmitting}
      />
    </div>
  );
};
