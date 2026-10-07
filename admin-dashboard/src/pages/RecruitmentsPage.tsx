import React, { useEffect, useState } from 'react';
import { api, getPublicPageUrl } from '../api/client';
import type { Recruitment, ApiResponse } from '../types';
import { Badge } from '../components/common/Badge';
import { Modal } from '../components/common/Modal';
import { ConfirmModal } from '../components/common/ConfirmModal';
import {
  Plus,
  Search,
  Edit2,
  Trash2,
  ExternalLink,
  Briefcase,
  AlertCircle,
  MapPin,
  DollarSign,
  Clock,
  Users,
} from 'lucide-react';

export const RecruitmentsPage: React.FC = () => {
  const [recruitments, setRecruitments] = useState<Recruitment[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [status, setStatus] = useState('');

  // Modals
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingItem, setEditingItem] = useState<Recruitment | null>(null);
  const [deleteTarget, setDeleteTarget] = useState<Recruitment | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);
  const [modalError, setModalError] = useState<string | null>(null);

  // Form State
  const [formTitle, setFormTitle] = useState('');
  const [formQuantity, setFormQuantity] = useState<number>(1);
  const [formEmploymentType, setFormEmploymentType] = useState('Toàn thời gian');
  const [formSalary, setFormSalary] = useState('Thỏa thuận');
  const [formLocation, setFormLocation] = useState('Hà Nội');
  const [formDescription, setFormDescription] = useState('');
  const [formRequirements, setFormRequirements] = useState('');
  const [formStatus, setFormStatus] = useState<'published' | 'draft'>('published');

  const fetchRecruitments = async () => {
    setIsLoading(true);
    try {
      const res = await api.get<ApiResponse<{ recruitments: Recruitment[]; total: number }>>(
        '/recruitments/index.php',
        {
          params: { search, status },
        }
      );
      if (res.data.success) {
        setRecruitments(res.data.data.recruitments);
      }
    } catch (e) {
      console.error('Lỗi tải danh sách tuyển dụng:', e);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchRecruitments();
  }, [search, status]);

  const handleOpenCreate = () => {
    setEditingItem(null);
    setFormTitle('');
    setFormQuantity(1);
    setFormEmploymentType('Toàn thời gian');
    setFormSalary('Thỏa thuận');
    setFormLocation('Hà Nội');
    setFormDescription('');
    setFormRequirements('');
    setFormStatus('published');
    setModalError(null);
    setIsModalOpen(true);
  };

  const handleOpenEdit = (item: Recruitment) => {
    setEditingItem(item);
    setFormTitle(item.title);
    setFormQuantity(item.quantity || 1);
    setFormEmploymentType(item.employment_type || 'Toàn thời gian');
    setFormSalary(item.salary || 'Thỏa thuận');
    setFormLocation(item.location || 'Hà Nội');
    setFormDescription(item.description || '');
    setFormRequirements(item.requirements || '');
    setFormStatus(item.status || 'published');
    setModalError(null);
    setIsModalOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formTitle.trim()) {
      setModalError('Vui lòng nhập chức danh tuyển dụng.');
      return;
    }

    if (formQuantity <= 0) {
      setModalError('Số lượng tuyển dụng phải lớn hơn hoặc bằng 1.');
      return;
    }

    setIsSubmitting(true);
    setFeedback(null);
    setModalError(null);

    const payload = {
      id: editingItem ? editingItem.id : undefined,
      title: formTitle.trim(),
      quantity: Number(formQuantity),
      employment_type: formEmploymentType.trim(),
      salary: formSalary.trim(),
      location: formLocation.trim(),
      description: formDescription.trim(),
      requirements: formRequirements.trim(),
      status: formStatus,
    };

    try {
      if (editingItem) {
        const res = await api.post('/recruitments/update.php', payload);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Cập nhật tin tuyển dụng thành công!' });
          setIsModalOpen(false);
          fetchRecruitments();
        }
      } else {
        const res = await api.post('/recruitments/create.php', payload);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Đăng tin tuyển dụng mới thành công!' });
          setIsModalOpen(false);
          fetchRecruitments();
        }
      }
    } catch (err: any) {
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi lưu tin tuyển dụng.';
      setModalError(msg);
      setFeedback({
        type: 'error',
        message: msg,
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleDelete = async () => {
    if (!deleteTarget) return;
    setIsSubmitting(true);
    try {
      const res = await api.post('/recruitments/delete.php', { id: deleteTarget.id });
      if (res.data.success) {
        setFeedback({ type: 'success', message: 'Đã xóa tin tuyển dụng thành công!' });
        setDeleteTarget(null);
        fetchRecruitments();
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể xóa tin tuyển dụng.',
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="space-y-6">
      {/* Page Title & Action */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
            <Briefcase className="w-6 h-6 text-amber-500" />
            <span>Quản Lý Tuyển Dụng &amp; Vị Trí Việc Làm</span>
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">
            Quản lý các đợt tuyển dụng nhân sự kỹ sư, thợ gia công, giám sát công trình
          </p>
        </div>
        <button
          onClick={handleOpenCreate}
          className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition self-start sm:self-auto cursor-pointer"
        >
          <Plus className="w-4 h-4" />
          <span>Thêm Vị Trí Tuyển Dụng</span>
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
            className="text-xs uppercase font-bold underline ml-4 cursor-pointer"
          >
            Đóng
          </button>
        </div>
      )}

      {/* Filters */}
      <div className="bg-white dark:bg-navy-900 p-4 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="relative flex-1 w-full">
          <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Tìm theo chức danh, địa điểm, hình thức, lương..."
            className="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <select
          value={status}
          onChange={(e) => setStatus(e.target.value)}
          className="px-3 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 w-full sm:w-auto"
        >
          <option value="">Tất cả trạng thái</option>
          <option value="published">Đã công khai (Hiển thị)</option>
          <option value="draft">Bản nháp (Ẩn)</option>
        </select>
      </div>

      {/* Recruitment Table / Cards */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm overflow-hidden">
        {isLoading ? (
          <div className="p-12 text-center">
            <div className="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
            <div className="mt-2 text-sm text-slate-400">Đang tải danh sách tuyển dụng...</div>
          </div>
        ) : recruitments.length === 0 ? (
          <div className="p-12 text-center text-slate-400 text-sm">
            Chưa có tin tuyển dụng nào được tạo.
          </div>
        ) : (
          <>
            {/* Mobile Card List (< 640px) */}
            <div className="sm:hidden divide-y divide-slate-100 dark:divide-navy-800">
              {recruitments.map((item) => (
                <div key={item.id} className="p-4 space-y-3">
                  <div className="flex items-start justify-between gap-2">
                    <div className="space-y-1">
                      <div className="flex items-center gap-2">
                        <Badge status={item.status} />
                        <span className="text-xs font-semibold text-amber-600 dark:text-amber-400 flex items-center gap-1">
                          <Users className="w-3.5 h-3.5" />
                          <span>{item.quantity} chỉ tiêu</span>
                        </span>
                      </div>
                      <h4 className="font-bold text-slate-900 dark:text-white text-base leading-snug">
                        {item.title}
                      </h4>
                    </div>
                  </div>

                  <div className="grid grid-cols-2 gap-2 text-xs text-slate-500 dark:text-slate-400 pt-1">
                    <div className="flex items-center gap-1.5 truncate">
                      <Clock className="w-3.5 h-3.5 text-slate-400 flex-shrink-0" />
                      <span className="truncate">{item.employment_type}</span>
                    </div>
                    <div className="flex items-center gap-1.5 truncate">
                      <MapPin className="w-3.5 h-3.5 text-slate-400 flex-shrink-0" />
                      <span className="truncate">{item.location}</span>
                    </div>
                    <div className="flex items-center gap-1.5 truncate col-span-2 text-emerald-600 dark:text-emerald-400 font-medium">
                      <DollarSign className="w-3.5 h-3.5 flex-shrink-0" />
                      <span>{item.salary}</span>
                    </div>
                  </div>

                  {item.description && (
                    <p className="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 bg-slate-50 dark:bg-navy-950 p-2 rounded-lg">
                      {item.description}
                    </p>
                  )}

                  <div className="flex items-center justify-between text-xs text-slate-400 pt-2 border-t border-slate-100 dark:border-navy-800">
                    <span>
                      {item.created_at ? new Date(item.created_at).toLocaleDateString('vi-VN') : ''}
                    </span>

                    <div className="flex items-center gap-1">
                      <a
                        href={getPublicPageUrl('/recruitment.php')}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="p-2.5 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition min-w-[40px] min-h-[40px] flex items-center justify-center"
                        title="Xem trang tuyển dụng"
                      >
                        <ExternalLink className="w-4 h-4" />
                      </a>
                      <button
                        onClick={() => handleOpenEdit(item)}
                        className="p-2.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition min-w-[40px] min-h-[40px] flex items-center justify-center cursor-pointer"
                        title="Sửa tin tuyển dụng"
                      >
                        <Edit2 className="w-4 h-4" />
                      </button>
                      <button
                        onClick={() => setDeleteTarget(item)}
                        className="p-2.5 text-rose-600 hover:text-rose-800 dark:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition min-w-[40px] min-h-[40px] flex items-center justify-center cursor-pointer"
                        title="Xóa tin tuyển dụng"
                      >
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Desktop Table (>= 640px) */}
            <div className="hidden sm:block overflow-x-auto">
              <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead className="bg-slate-50 dark:bg-navy-950 text-xs uppercase font-bold text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-navy-800">
                  <tr>
                    <th className="px-6 py-4">Chức danh / Vị trí</th>
                    <th className="px-4 py-4 text-center">Số lượng</th>
                    <th className="px-4 py-4">Loại hình / Địa điểm</th>
                    <th className="px-4 py-4">Mức lương</th>
                    <th className="px-4 py-4">Trạng thái</th>
                    <th className="px-4 py-4">Ngày đăng</th>
                    <th className="px-6 py-4 text-right">Thao tác</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-navy-800">
                  {recruitments.map((item) => (
                    <tr
                      key={item.id}
                      className="hover:bg-slate-50/80 dark:hover:bg-navy-800/40 transition"
                    >
                      <td className="px-6 py-4">
                        <div className="font-bold text-slate-900 dark:text-white text-base">
                          {item.title}
                        </div>
                        {item.description ? (
                          <div className="text-xs text-slate-400 line-clamp-1 max-w-sm">
                            {item.description}
                          </div>
                        ) : null}
                      </td>
                      <td className="px-4 py-4 text-center whitespace-nowrap">
                        <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                          {item.quantity} người
                        </span>
                      </td>
                      <td className="px-4 py-4 text-xs whitespace-nowrap">
                        <div className="font-medium text-slate-800 dark:text-slate-200">
                          {item.employment_type}
                        </div>
                        <div className="text-slate-400 flex items-center gap-1 mt-0.5">
                          <MapPin className="w-3 h-3 text-slate-400" />
                          <span>{item.location}</span>
                        </div>
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        {item.salary}
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap">
                        <Badge status={item.status} />
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap text-xs text-slate-400">
                        {item.created_at
                          ? new Date(item.created_at).toLocaleDateString('vi-VN')
                          : '—'}
                      </td>
                      <td className="px-6 py-4 text-right whitespace-nowrap">
                        <div className="flex items-center justify-end gap-1.5">
                          <a
                            href={getPublicPageUrl('/recruitment.php')}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
                            title="Xem trang tuyển dụng"
                          >
                            <ExternalLink className="w-4 h-4" />
                          </a>
                          <button
                            onClick={() => handleOpenEdit(item)}
                            className="p-2 text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition cursor-pointer"
                            title="Sửa tin tuyển dụng"
                          >
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => setDeleteTarget(item)}
                            className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition cursor-pointer"
                            title="Xóa tin tuyển dụng"
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
          </>
        )}
      </div>

      {/* Modal Create / Edit */}
      <Modal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        title={editingItem ? 'Chỉnh Sửa Vị Trí Tuyển Dụng' : 'Thêm Vị Trí Tuyển Dụng Mới'}
        maxWidth="3xl"
      >
        <form onSubmit={handleSubmit} className="space-y-4">
          {modalError && (
            <div className="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 text-xs flex items-start gap-2">
              <AlertCircle className="w-4 h-4 mt-0.5 flex-shrink-0 text-rose-500" />
              <span className="flex-1 font-medium">{modalError}</span>
            </div>
          )}

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div className="sm:col-span-2">
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Chức Danh Tuyển Dụng *
              </label>
              <input
                type="text"
                required
                value={formTitle}
                onChange={(e) => setFormTitle(e.target.value)}
                placeholder="VD: Kỹ Sư Thiết Kế Kết Cấu Thép"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Số Lượng Cần Tuyển *
              </label>
              <input
                type="number"
                min="1"
                required
                value={formQuantity}
                onChange={(e) => setFormQuantity(Math.max(1, parseInt(e.target.value) || 1))}
                placeholder="1"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Loại Hình Làm Việc
              </label>
              <input
                type="text"
                value={formEmploymentType}
                onChange={(e) => setFormEmploymentType(e.target.value)}
                placeholder="Toàn thời gian / Bán thời gian / Nhà máy..."
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Mức Lương
              </label>
              <input
                type="text"
                value={formSalary}
                onChange={(e) => setFormSalary(e.target.value)}
                placeholder="18 - 25 Triệu / tháng, Thỏa thuận..."
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Địa Điểm Làm Việc
              </label>
              <input
                type="text"
                value={formLocation}
                onChange={(e) => setFormLocation(e.target.value)}
                placeholder="Hà Nội / Hưng Yên / Theo dự án..."
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Trạng Thái Tin Tuyển Dụng
            </label>
            <select
              value={formStatus}
              onChange={(e) => setFormStatus(e.target.value as 'published' | 'draft')}
              className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
            >
              <option value="published">Công khai (Hiển thị trên website)</option>
              <option value="draft">Bản nháp (Lưu tạm, chưa đăng)</option>
            </select>
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Mô Tả Công Việc
            </label>
            <textarea
              rows={4}
              value={formDescription}
              onChange={(e) => setFormDescription(e.target.value)}
              placeholder="Nội dung mô tả trách nhiệm công việc, nhiệm vụ chính (mỗi dòng một ý)..."
              className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
            />
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Yêu Cầu Ứng Viên
            </label>
            <textarea
              rows={4}
              value={formRequirements}
              onChange={(e) => setFormRequirements(e.target.value)}
              placeholder="Yêu cầu về bằng cấp, kinh nghiệm, kỹ năng (Tekla, AutoCAD...), phẩm chất cá nhân..."
              className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
            />
          </div>

          <div className="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-navy-800">
            <button
              type="button"
              onClick={() => setIsModalOpen(false)}
              className="px-4 py-2 text-sm font-medium rounded-xl border border-slate-300 dark:border-navy-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-navy-800 transition cursor-pointer"
            >
              Hủy bỏ
            </button>
            <button
              type="submit"
              disabled={isSubmitting}
              className="px-5 py-2 text-sm font-bold rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 shadow-md shadow-amber-500/20 transition disabled:opacity-50 cursor-pointer"
            >
              {isSubmitting ? 'Đang lưu...' : editingItem ? 'Lưu Thay Đổi' : 'Đăng Tin Tuyển Dụng'}
            </button>
          </div>
        </form>
      </Modal>

      {/* Delete Confirmation */}
      <ConfirmModal
        isOpen={Boolean(deleteTarget)}
        onClose={() => setDeleteTarget(null)}
        onConfirm={handleDelete}
        title="Xóa Vị Trí Tuyển Dụng"
        message={`Bạn có chắc muốn xóa tin tuyển dụng "${deleteTarget?.title}"?`}
        confirmText="Xác nhận xóa"
        isDangerous
        isLoading={isSubmitting}
      />
    </div>
  );
};
