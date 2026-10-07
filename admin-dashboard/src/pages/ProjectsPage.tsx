import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, getImageUrl, getPublicPageUrl } from '../api/client';
import type { Project, ApiResponse } from '../types';
import { Badge } from '../components/common/Badge';
import { Modal } from '../components/common/Modal';
import { ConfirmModal } from '../components/common/ConfirmModal';
import {
  Plus,
  Search,
  Edit2,
  Trash2,
  ExternalLink,
  Upload,
  MapPin,
  FilePlus2,
  Calendar,
  Image as ImageIcon,
  AlertCircle,
  X,
} from 'lucide-react';

const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
const ALLOWED_IMAGE_EXTENSIONS = ['.jpg', '.jpeg', '.png', '.webp', '.gif'];
const MAX_IMAGE_SIZE_BYTES = 8 * 1024 * 1024; // 8MB

const validateImageFile = (file: File): string | null => {
  const extension = '.' + (file.name.split('.').pop() || '').toLowerCase();
  const isValidExt = ALLOWED_IMAGE_EXTENSIONS.includes(extension);
  const isValidType = ALLOWED_IMAGE_TYPES.includes(file.type);
  if (!isValidExt && !isValidType) {
    return 'Chỉ chấp nhận tệp hình ảnh định dạng JPG, JPEG, PNG, WEBP hoặc GIF.';
  }
  if (file.size > MAX_IMAGE_SIZE_BYTES) {
    const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
    return `Kích thước ảnh vượt quá giới hạn 8MB (hiện tại ${sizeMb}MB). Vui lòng chọn ảnh nhỏ hơn.`;
  }
  return null;
};

const ProjectThumbnail: React.FC<{
  image?: string | null;
  title: string;
  className?: string;
}> = ({ image, title, className = 'w-14 h-14' }) => {
  const [imgError, setImgError] = useState(false);
  const url = getImageUrl(image);

  useEffect(() => {
    setImgError(false);
  }, [image]);

  if (!url || imgError) {
    return (
      <div
        className={`${className} rounded-xl bg-slate-100 dark:bg-navy-800 flex flex-col items-center justify-center p-1 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-navy-700 flex-shrink-0 select-none`}
        title="Chưa có ảnh"
      >
        <ImageIcon className="w-5 h-5 mb-0.5 opacity-60" />
        <span className="text-[9px] font-medium leading-none text-center">Chưa có ảnh</span>
      </div>
    );
  }

  return (
    <div className={`${className} rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0 border border-slate-200 dark:border-navy-700`}>
      <img
        src={url}
        alt={title}
        className="w-full h-full object-cover"
        onError={() => setImgError(true)}
      />
    </div>
  );
};

export const ProjectsPage: React.FC = () => {
  const [projects, setProjects] = useState<Project[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [category, setCategory] = useState('');
  const [sector, setSector] = useState('');
  const [status, setStatus] = useState('');

  // Modals state
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingProject, setEditingProject] = useState<Project | null>(null);
  const [deleteTarget, setDeleteTarget] = useState<Project | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);
  const [modalError, setModalError] = useState<string | null>(null);

  // Form fields
  const [formTitle, setFormTitle] = useState('');
  const [formCategory, setFormCategory] = useState('Nhà kết cấu thép');
  const [formClient, setFormClient] = useState('');
  const [formLocation, setFormLocation] = useState('');
  const [formStartDate, setFormStartDate] = useState('');
  const [formCompletionDate, setFormCompletionDate] = useState('');
  const [formDescription, setFormDescription] = useState('');
  const [formContent, setFormContent] = useState('');
  const [formStatus, setFormStatus] = useState<'published' | 'draft'>('published');
  const [formImageFile, setFormImageFile] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState<string>('');

  const coKhiCategories = [
    'Nhà kết cấu thép',
    'Cầu thang - Ban công',
    'Mái tôn - Mái che',
    'Nhà cơi nới - Gác lửng',
    'Thang thoát hiểm',
    'Nhà xe - Mái che',
    'Mái kính',
    'Sắt mỹ thuật',
    'Cửa các loại',
    'Cơ khí chế tạo',
    'Kết cấu thép',
    'Cơ khí xây dựng',
  ];

  const xayDungCategories = [
    'Xây nhà trọn gói',
    'Nội ngoại thất',
    'Cải tạo & Phá dỡ',
    'Xây dựng dân dụng',
    'Xây dựng công nghiệp',
  ];

  const allCategories = [...coKhiCategories, ...xayDungCategories];

  const fetchProjects = async () => {
    setIsLoading(true);
    try {
      const res = await api.get<ApiResponse<{ projects: Project[] }>>('/projects/index.php', {
        params: {
          search,
          category,
          sector,
          status,
        },
      });
      if (res.data.success) {
        setProjects(res.data.data.projects);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchProjects();
  }, [search, category, sector, status]);

  const handleOpenCreate = () => {
    setEditingProject(null);
    setFormTitle('');
    setFormCategory('Nhà kết cấu thép');
    setFormClient('Doanh nghiệp / Cá nhân');
    setFormLocation('Hà Nội, Việt Nam');
    setFormStartDate('');
    setFormCompletionDate('');
    setFormDescription('');
    setFormContent('');
    setFormStatus('published');
    setFormImageFile(null);
    setImagePreview('');
    setModalError(null);
    setIsModalOpen(true);
  };

  const handleOpenEdit = (p: Project) => {
    setEditingProject(p);
    setFormTitle(p.title);
    setFormCategory(p.category);
    setFormClient(p.client || '');
    setFormLocation(p.location || '');
    setFormStartDate(p.start_date || '');
    setFormCompletionDate(p.completion_date || '');
    setFormDescription(p.description || '');
    setFormContent(p.content || '');
    setFormStatus(p.status || 'published');
    setFormImageFile(null);
    setImagePreview(getImageUrl(p.image));
    setModalError(null);
    setIsModalOpen(true);
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setModalError(null);
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      const errorMsg = validateImageFile(file);
      if (errorMsg) {
        setModalError(errorMsg);
        e.target.value = '';
        return;
      }
      setFormImageFile(file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const handleSubmitForm = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formTitle.trim()) {
      setModalError('Vui lòng nhập tên công trình.');
      return;
    }

    if (formImageFile) {
      const errorMsg = validateImageFile(formImageFile);
      if (errorMsg) {
        setModalError(errorMsg);
        return;
      }
    }

    setIsSubmitting(true);
    setFeedback(null);
    setModalError(null);

    const formData = new FormData();
    formData.append('title', formTitle.trim());
    formData.append('category', formCategory);
    formData.append('client', formClient.trim());
    formData.append('location', formLocation.trim());
    formData.append('start_date', formStartDate || '');
    formData.append('completion_date', formCompletionDate || '');
    formData.append('description', formDescription.trim());
    formData.append('content', formContent.trim());
    formData.append('status', formStatus);

    if (formImageFile) {
      formData.append('image', formImageFile);
    } else if (editingProject && !imagePreview) {
      formData.append('image', '');
    }

    try {
      if (editingProject) {
        formData.append('id', String(editingProject.id));
        const res = await api.post('/projects/update.php', formData);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Cập nhật công trình thành công!' });
          setIsModalOpen(false);
          fetchProjects();
        }
      } else {
        const res = await api.post('/projects/create.php', formData);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Tạo công trình mới thành công!' });
          setIsModalOpen(false);
          fetchProjects();
        }
      }
    } catch (err: any) {
      const msg = err.response?.data?.message || 'Có lỗi xảy ra khi lưu công trình.';
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
      const res = await api.post('/projects/delete.php', { id: deleteTarget.id });
      if (res.data.success) {
        setFeedback({ type: 'success', message: 'Đã xóa công trình thành công!' });
        setDeleteTarget(null);
        fetchProjects();
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể xóa công trình.',
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="space-y-6">
      {/* Page Title & Actions */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold text-slate-900 dark:text-white">
            Quản Lý Công Trình &amp; Dự Án
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">
            Hồ sơ năng lực thực tế, công trình kết cấu thép và xây dựng đã hoàn thiện
          </p>
        </div>
        <button
          onClick={handleOpenCreate}
          className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition self-start sm:self-auto"
        >
          <Plus className="w-4 h-4" />
          <span>Thêm Công Trình Mới</span>
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
            placeholder="Tìm theo tên công trình, chủ đầu tư, địa điểm..."
            className="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-2 w-full md:w-auto">
          {/* Sector filter */}
          <select
            value={sector}
            onChange={(e) => setSector(e.target.value)}
            className="w-full px-3 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 min-h-[40px]"
          >
            <option value="">Tất cả mảng (Cơ khí &amp; XD)</option>
            <option value="co_khi">Khối Cơ Khí</option>
            <option value="xay_dung">Khối Xây Dựng</option>
          </select>

          {/* Category filter */}
          <select
            value={category}
            onChange={(e) => setCategory(e.target.value)}
            className="w-full px-3 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 min-h-[40px]"
          >
            <option value="">Tất cả hạng mục</option>
            {allCategories.map((c) => (
              <option key={c} value={c}>
                {c}
              </option>
            ))}
          </select>

          {/* Status filter */}
          <select
            value={status}
            onChange={(e) => setStatus(e.target.value)}
            className="w-full px-3 py-2 text-xs font-medium rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 min-h-[40px]"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="published">Đã công khai</option>
            <option value="draft">Bản nháp</option>
          </select>
        </div>
      </div>

      {/* Projects Table & Mobile Cards */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm overflow-hidden">
        {isLoading ? (
          <div className="p-12 text-center">
            <div className="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
            <div className="mt-2 text-sm text-slate-400">Đang tải danh sách công trình...</div>
          </div>
        ) : projects.length === 0 ? (
          <div className="p-12 text-center text-slate-400 text-sm">
            Không tìm thấy công trình nào phù hợp với điều kiện tìm kiếm.
          </div>
        ) : (
          <>
            {/* Mobile Card List (< 640px) */}
            <div className="sm:hidden divide-y divide-slate-100 dark:divide-navy-800">
              {projects.map((p) => (
                <div key={p.id} className="p-4 space-y-3">
                  <div className="flex gap-3 items-start">
                    <ProjectThumbnail
                      image={p.image}
                      title={p.title}
                      className="w-16 h-16"
                    />
                    <div className="min-w-0 flex-1">
                      <div className="font-bold text-slate-900 dark:text-white text-sm line-clamp-2">
                        {p.title}
                      </div>
                      <div className="flex flex-wrap gap-1.5 mt-1.5">
                        <span className="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                          {p.category}
                        </span>
                        <Badge status={p.status} />
                        {p.detail_mode === 'custom' && (
                          <span className="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border border-amber-300 dark:border-amber-800">
                            Builder
                          </span>
                        )}
                      </div>
                    </div>
                  </div>

                  <div className="bg-slate-50 dark:bg-navy-950/60 p-2.5 rounded-xl space-y-1 text-xs">
                    <div className="font-semibold text-slate-800 dark:text-slate-200 truncate">
                      <span className="text-slate-400 font-normal">Chủ đầu tư: </span>
                      {p.client || 'Chưa cập nhật'}
                    </div>
                    <div className="text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                      <MapPin className="w-3.5 h-3.5 flex-shrink-0 text-amber-500" />
                      <span className="truncate">{p.location || 'Hà Nội'}</span>
                    </div>
                    {(p.start_date || p.completion_date) && (
                      <div className="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-mono text-[11px]">
                        <Calendar className="w-3.5 h-3.5 flex-shrink-0 text-amber-500" />
                        <span>
                          {p.start_date ? p.start_date.split('-').reverse().join('/') : '...'} - {p.completion_date ? p.completion_date.split('-').reverse().join('/') : '...'}
                        </span>
                      </div>
                    )}
                  </div>

                  {/* Actions */}
                  <div className="flex items-center justify-end gap-1.5 pt-1">
                    <Link
                      to={`/projects/${p.id}/detail-editor`}
                      className="px-2.5 py-1.5 text-xs text-amber-600 hover:text-amber-700 dark:hover:text-amber-400 rounded-lg bg-amber-50 dark:bg-amber-950/50 transition font-bold flex items-center gap-1 min-h-[38px]"
                      title="Soạn trang chi tiết"
                    >
                      <FilePlus2 className="w-4 h-4" />
                      <span>Builder</span>
                    </Link>
                    <a
                      href={getPublicPageUrl(`/project-detail.php?id=${p.id}`)}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition min-w-[38px] min-h-[38px] flex items-center justify-center"
                      title="Xem trên website"
                    >
                      <ExternalLink className="w-4 h-4" />
                    </a>
                    <button
                      onClick={() => handleOpenEdit(p)}
                      className="p-2 text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition min-w-[38px] min-h-[38px] flex items-center justify-center"
                      title="Chỉnh sửa công trình"
                    >
                      <Edit2 className="w-4 h-4" />
                    </button>
                    <button
                      onClick={() => setDeleteTarget(p)}
                      className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition min-w-[38px] min-h-[38px] flex items-center justify-center"
                      title="Xóa công trình"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              ))}
            </div>

            {/* Desktop Table (>= 640px) */}
            <div className="hidden sm:block overflow-x-auto">
              <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead className="bg-slate-50 dark:bg-navy-950 text-xs uppercase font-bold text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-navy-800">
                  <tr>
                    <th className="px-6 py-4">Công trình</th>
                    <th className="px-4 py-4">Phân loại</th>
                    <th className="px-4 py-4">Chủ đầu tư / Địa điểm</th>
                    <th className="px-4 py-4">Trạng thái</th>
                    <th className="px-6 py-4 text-right">Thao tác</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-navy-800">
                  {projects.map((p) => (
                    <tr
                      key={p.id}
                      className="hover:bg-slate-50/80 dark:hover:bg-navy-800/40 transition"
                    >
                      <td className="px-6 py-4">
                        <div className="flex items-center gap-3">
                          <ProjectThumbnail
                            image={p.image}
                            title={p.title}
                            className="w-14 h-14"
                          />
                          <div>
                            <div className="font-bold text-slate-900 dark:text-white line-clamp-1 flex items-center gap-1.5">
                              <span>{p.title}</span>
                              {p.detail_mode === 'custom' && (
                                <span className="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border border-amber-300 dark:border-amber-800">
                                  Builder
                                </span>
                              )}
                            </div>
                            <div className="text-xs text-slate-400 mt-0.5 line-clamp-1">
                              {p.description}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap">
                        <span className="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                          {p.category}
                        </span>
                      </td>
                      <td className="px-4 py-4 text-xs">
                        <div className="font-semibold text-slate-800 dark:text-slate-200">
                          {p.client || 'Chưa cập nhật'}
                        </div>
                        <div className="text-slate-400 flex items-center gap-1 mt-0.5">
                          <MapPin className="w-3 h-3 flex-shrink-0" />
                          <span>{p.location || 'Hà Nội'}</span>
                        </div>
                        {(p.start_date || p.completion_date) && (
                          <div className="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-1 font-mono">
                            <Calendar className="w-3 h-3 flex-shrink-0 text-amber-500" />
                            <span>
                              {p.start_date ? p.start_date.split('-').reverse().join('/') : '...'} - {p.completion_date ? p.completion_date.split('-').reverse().join('/') : '...'}
                            </span>
                          </div>
                        )}
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap">
                        <Badge status={p.status} />
                      </td>
                      <td className="px-6 py-4 text-right whitespace-nowrap">
                        <div className="flex items-center justify-end gap-1.5">
                          <Link
                            to={`/projects/${p.id}/detail-editor`}
                            className="p-2 text-amber-600 hover:text-amber-700 dark:hover:text-amber-400 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-950/50 transition font-bold"
                            title="Soạn trang chi tiết (Nội dung chi tiết nâng cao)"
                          >
                            <FilePlus2 className="w-4 h-4" />
                          </Link>
                          <a
                            href={getPublicPageUrl(`/project-detail.php?id=${p.id}`)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
                            title="Xem trên website"
                          >
                            <ExternalLink className="w-4 h-4" />
                          </a>
                          <button
                            onClick={() => handleOpenEdit(p)}
                            className="p-2 text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition"
                            title="Chỉnh sửa công trình"
                          >
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => setDeleteTarget(p)}
                            className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition"
                            title="Xóa công trình"
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


      {/* Create / Edit Modal */}
      <Modal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        title={editingProject ? 'Chỉnh Sửa Hồ Sơ Công Trình' : 'Đăng Công Trình Hoàn Thiện Mới'}
        maxWidth="3xl"
      >
        <form onSubmit={handleSubmitForm} className="space-y-4">
          {modalError && (
            <div className="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 text-xs flex items-start gap-2">
              <AlertCircle className="w-4 h-4 mt-0.5 flex-shrink-0 text-rose-500" />
              <span className="flex-1 font-medium">{modalError}</span>
            </div>
          )}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="md:col-span-2">
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Tên / Tiêu đề công trình *
              </label>
              <input
                type="text"
                required
                value={formTitle}
                onChange={(e) => setFormTitle(e.target.value)}
                placeholder="VD: Thi Công Nhà Xưởng Kết Cấu Thép 5000m2 - KCN Thăng Long"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Hạng mục phân loại *
              </label>
              <select
                value={formCategory}
                onChange={(e) => setFormCategory(e.target.value)}
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              >
                <optgroup label="Khối Cơ Khí Xây Dựng">
                  {coKhiCategories.map((c) => (
                    <option key={c} value={c}>
                      {c}
                    </option>
                  ))}
                </optgroup>
                <optgroup label="Khối Xây Dựng &amp; Hoàn Thiện">
                  {xayDungCategories.map((c) => (
                    <option key={c} value={c}>
                      {c}
                    </option>
                  ))}
                </optgroup>
              </select>
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Trạng thái hiển thị
              </label>
              <select
                value={formStatus}
                onChange={(e) => setFormStatus(e.target.value as any)}
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              >
                <option value="published">Công khai (Hiển thị website)</option>
                <option value="draft">Bản nháp (Ẩn)</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Chủ đầu tư / Khách hàng
              </label>
              <input
                type="text"
                value={formClient}
                onChange={(e) => setFormClient(e.target.value)}
                placeholder="VD: Tập Đoàn Hanwha / Doanh nghiệp FDI"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Địa điểm thi công
              </label>
              <input
                type="text"
                value={formLocation}
                onChange={(e) => setFormLocation(e.target.value)}
                placeholder="VD: KCN Quang Minh, Mê Linh, Hà Nội"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Ngày bắt đầu thi công
              </label>
              <input
                type="date"
                value={formStartDate}
                onChange={(e) => setFormStartDate(e.target.value)}
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Ngày hoàn thành
              </label>
              <input
                type="date"
                value={formCompletionDate}
                onChange={(e) => setFormCompletionDate(e.target.value)}
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            {/* Image upload with preview */}
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Hình ảnh đại diện công trình
              </label>
              <div className="flex items-center gap-3">
                {imagePreview ? (
                  <div className="relative w-16 h-12 rounded-lg bg-slate-100 dark:bg-navy-800 overflow-hidden border border-slate-200 dark:border-navy-700 flex-shrink-0 group">
                    <img src={imagePreview} alt="Preview" className="w-full h-full object-cover" />
                    <button
                      type="button"
                      onClick={() => {
                        setFormImageFile(null);
                        setImagePreview('');
                      }}
                      className="absolute inset-0 bg-navy-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition cursor-pointer"
                      title="Gỡ ảnh"
                    >
                      <X className="w-3.5 h-3.5" />
                    </button>
                  </div>
                ) : (
                  <div className="w-16 h-12 rounded-lg bg-slate-100 dark:bg-navy-800 flex items-center justify-center border border-dashed border-slate-300 dark:border-navy-700 text-slate-400 flex-shrink-0">
                    <ImageIcon className="w-4 h-4 opacity-50" />
                  </div>
                )}
                <div className="flex-1 min-w-0">
                  <label className="flex items-center justify-center gap-2 px-3 py-2 border border-dashed border-slate-300 dark:border-navy-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-navy-800 text-xs text-slate-600 dark:text-slate-300 transition">
                    <Upload className="w-4 h-4 text-amber-500" />
                    <span>{imagePreview ? 'Đổi tệp ảnh khác' : 'Chọn tệp ảnh mới'}</span>
                    <input
                      type="file"
                      accept="image/jpeg,image/png,image/webp,image/gif"
                      onChange={handleImageChange}
                      className="hidden"
                    />
                  </label>
                  <p className="text-[11px] text-slate-400 mt-1">
                    JPG, JPEG, PNG, WEBP, GIF (tối đa 8MB)
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Mô tả ngắn gọn dự án *
            </label>
            <textarea
              required
              rows={2}
              value={formDescription}
              onChange={(e) => setFormDescription(e.target.value)}
              placeholder="Tóm tắt quy mô diện tích, khối lượng kết cấu thép, đặc điểm nổi bật..."
              className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
            />
          </div>

          <div>
            <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
              Nội dung hồ sơ kỹ thuật chi tiết
            </label>
            <textarea
              rows={4}
              value={formContent}
              onChange={(e) => setFormContent(e.target.value)}
              placeholder="Chi tiết phương án gia công, tiến độ thực hiện, tiêu chuẩn nghiệm thu..."
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
              {isSubmitting ? 'Đang lưu...' : editingProject ? 'Lưu Thay Đổi' : 'Tạo Công Trình'}
            </button>
          </div>
        </form>
      </Modal>

      {/* Delete Confirmation Modal */}
      <ConfirmModal
        isOpen={Boolean(deleteTarget)}
        onClose={() => setDeleteTarget(null)}
        onConfirm={handleDelete}
        title="Xóa Công Trình"
        message={`Bạn có chắc chắn muốn xóa vĩnh viễn công trình "${deleteTarget?.title}"? Hành động này sẽ xóa dữ liệu và hình ảnh đính kèm khỏi máy chủ.`}
        confirmText="Xác nhận xóa"
        isDangerous
        isLoading={isSubmitting}
      />
    </div>
  );
};
