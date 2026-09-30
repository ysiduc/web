import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import type { Quote, ApiResponse } from '../types';
import { Badge } from '../components/common/Badge';
import { Modal } from '../components/common/Modal';
import { ConfirmModal } from '../components/common/ConfirmModal';
import {
  Search,
  Trash2,
  Phone,
  Mail,
  MapPin,
  Eye,
} from 'lucide-react';

export const QuotesPage: React.FC = () => {
  const [quotes, setQuotes] = useState<Quote[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [status, setStatus] = useState('');

  // Modals
  const [selectedQuote, setSelectedQuote] = useState<Quote | null>(null);
  const [deleteTarget, setDeleteTarget] = useState<Quote | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  const fetchQuotes = async () => {
    setIsLoading(true);
    try {
      const res = await api.get<ApiResponse<{ quotes: Quote[] }>>('/quotes/index.php', {
        params: { search, status },
      });
      if (res.data.success) {
        setQuotes(res.data.data.quotes);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchQuotes();
  }, [search, status]);

  const handleUpdateStatus = async (id: number, nextStatus: Quote['status']) => {
    try {
      const res = await api.post('/quotes/update_status.php', { id, status: nextStatus });
      if (res.data.success) {
        setQuotes((prev) =>
          prev.map((q) => (q.id === id ? { ...q, status: nextStatus } : q))
        );
        if (selectedQuote && selectedQuote.id === id) {
          setSelectedQuote({ ...selectedQuote, status: nextStatus });
        }
        setFeedback({
          type: 'success',
          message: `Đã cập nhật trạng thái yêu cầu #${id} thành công!`,
        });
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể cập nhật trạng thái.',
      });
    }
  };

  const handleDelete = async () => {
    if (!deleteTarget) return;
    setIsSubmitting(true);
    try {
      const res = await api.post('/quotes/delete.php', { id: deleteTarget.id });
      if (res.data.success) {
        setFeedback({ type: 'success', message: 'Đã xóa yêu cầu báo giá thành công!' });
        setDeleteTarget(null);
        if (selectedQuote?.id === deleteTarget.id) setSelectedQuote(null);
        fetchQuotes();
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể xóa báo giá.',
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
            Quản Lý Yêu Cầu Báo Giá
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">
            Dữ liệu khách hàng đăng ký nhận bảng dự toán chi phí thi công &amp; gia công kết cấu
          </p>
        </div>
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

      {/* Filters */}
      <div className="bg-white dark:bg-navy-900 p-4 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
        <div className="relative flex-1">
          <Search className="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Tìm theo họ tên, số điện thoại, email, dịch vụ..."
            className="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <div className="flex flex-wrap items-center gap-1.5">
          {[
            { val: '', label: 'Tất cả' },
            { val: 'new', label: 'Mới gửi' },
            { val: 'processing', label: 'Đang xử lý' },
            { val: 'completed', label: 'Hoàn tất' },
            { val: 'canceled', label: 'Đã hủy' },
          ].map((tab) => (
            <button
              key={tab.val}
              onClick={() => setStatus(tab.val)}
              className={`px-3 py-1.5 rounded-xl text-xs font-semibold transition ${
                status === tab.val
                  ? 'bg-amber-500 text-navy-950 font-bold shadow-sm'
                  : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'
              }`}
            >
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Quotes Table */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm overflow-hidden">
        {isLoading ? (
          <div className="p-12 text-center">
            <div className="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
            <div className="mt-2 text-sm text-slate-400">Đang tải danh sách báo giá...</div>
          </div>
        ) : quotes.length === 0 ? (
          <div className="p-12 text-center text-slate-400 text-sm">
            Không tìm thấy yêu cầu báo giá nào.
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
              <thead className="bg-slate-50 dark:bg-navy-950 text-xs uppercase font-bold text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-navy-800">
                <tr>
                  <th className="px-6 py-4">Khách hàng</th>
                  <th className="px-4 py-4">Liên hệ</th>
                  <th className="px-4 py-4">Dịch vụ yêu cầu</th>
                  <th className="px-4 py-4">Trạng thái</th>
                  <th className="px-4 py-4">Ngày gửi</th>
                  <th className="px-6 py-4 text-right">Thao tác</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 dark:divide-navy-800">
                {quotes.map((q) => (
                  <tr
                    key={q.id}
                    className="hover:bg-slate-50/80 dark:hover:bg-navy-800/40 transition cursor-pointer"
                    onClick={() => setSelectedQuote(q)}
                  >
                    <td className="px-6 py-4">
                      <div className="font-bold text-slate-900 dark:text-white">
                        {q.fullname}
                      </div>
                      <div className="text-xs text-slate-400 mt-0.5 line-clamp-1 max-w-xs">
                        {q.message || 'Không có ghi chú thêm.'}
                      </div>
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap text-xs" onClick={(e) => e.stopPropagation()}>
                      <div className="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                        <Phone className="w-3.5 h-3.5 text-amber-500 flex-shrink-0" />
                        <a href={`tel:${q.phone}`} className="font-mono hover:text-amber-600 hover:underline">
                          {q.phone}
                        </a>
                      </div>
                      {q.email && (
                        <div className="flex items-center gap-1.5 text-slate-400 mt-1">
                          <Mail className="w-3.5 h-3.5 flex-shrink-0" />
                          <a href={`mailto:${q.email}`} className="hover:underline truncate max-w-[160px]">
                            {q.email}
                          </a>
                        </div>
                      )}
                    </td>
                    <td className="px-4 py-4">
                      <div className="font-semibold text-slate-800 dark:text-slate-200 text-xs">
                        {q.service_type || 'Tư vấn tổng thể'}
                      </div>
                      {q.project_location && (
                        <div className="text-slate-400 text-[11px] flex items-center gap-1 mt-0.5">
                          <MapPin className="w-3 h-3" />
                          <span>{q.project_location}</span>
                        </div>
                      )}
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap" onClick={(e) => e.stopPropagation()}>
                      <select
                        value={q.status}
                        onChange={(e) => handleUpdateStatus(q.id, e.target.value as any)}
                        className="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 dark:border-navy-700 bg-white dark:bg-navy-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-amber-500"
                      >
                        <option value="new">Mới gửi</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="completed">Hoàn tất</option>
                        <option value="canceled">Đã hủy</option>
                      </select>
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap text-xs text-slate-400">
                      {new Date(q.created_at).toLocaleDateString('vi-VN')}
                    </td>
                    <td className="px-6 py-4 text-right whitespace-nowrap" onClick={(e) => e.stopPropagation()}>
                      <div className="flex items-center justify-end gap-1.5">
                        <button
                          onClick={() => setSelectedQuote(q)}
                          className="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
                          title="Xem chi tiết"
                        >
                          <Eye className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => setDeleteTarget(q)}
                          className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition"
                          title="Xóa yêu cầu"
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

      {/* Quote Detail Modal */}
      <Modal
        isOpen={Boolean(selectedQuote)}
        onClose={() => setSelectedQuote(null)}
        title={`Chi Tiết Yêu Cầu Báo Giá #${selectedQuote?.id}`}
        maxWidth="lg"
      >
        {selectedQuote && (
          <div className="space-y-4">
            <div className="p-4 rounded-2xl bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-navy-800 space-y-3">
              <div className="flex items-center justify-between">
                <div>
                  <div className="text-xs text-slate-400">Họ và tên khách hàng</div>
                  <div className="text-base font-bold text-slate-900 dark:text-white">
                    {selectedQuote.fullname}
                  </div>
                </div>
                <Badge status={selectedQuote.status} />
              </div>

              <div className="grid grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-200 dark:border-navy-800">
                <div>
                  <div className="text-slate-400 mb-0.5">Số điện thoại:</div>
                  <a
                    href={`tel:${selectedQuote.phone}`}
                    className="font-bold font-mono text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1"
                  >
                    <Phone className="w-3.5 h-3.5" />
                    {selectedQuote.phone}
                  </a>
                </div>
                <div>
                  <div className="text-slate-400 mb-0.5">Email:</div>
                  <a
                    href={`mailto:${selectedQuote.email}`}
                    className="font-medium text-slate-700 dark:text-slate-300 hover:underline truncate block"
                  >
                    {selectedQuote.email || 'Chưa cung cấp'}
                  </a>
                </div>
              </div>
            </div>

            <div className="space-y-2 text-xs">
              <div>
                <span className="font-bold text-slate-700 dark:text-slate-300">Dịch vụ quan tâm: </span>
                <span className="text-slate-600 dark:text-slate-400 font-semibold">{selectedQuote.service_type || 'Tư vấn chung'}</span>
              </div>
              <div>
                <span className="font-bold text-slate-700 dark:text-slate-300">Địa điểm dự án: </span>
                <span className="text-slate-600 dark:text-slate-400">{selectedQuote.project_location || 'Hà Nội / Toàn quốc'}</span>
              </div>
              <div>
                <span className="font-bold text-slate-700 dark:text-slate-300">Thời gian gửi: </span>
                <span className="text-slate-600 dark:text-slate-400">{new Date(selectedQuote.created_at).toLocaleString('vi-VN')}</span>
              </div>
            </div>

            <div>
              <div className="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Nội dung yêu cầu / Ghi chú từ khách:
              </div>
              <div className="p-3.5 rounded-xl bg-slate-100 dark:bg-navy-950 border border-slate-200 dark:border-navy-800 text-xs text-slate-800 dark:text-slate-200 whitespace-pre-wrap leading-relaxed">
                {selectedQuote.message || 'Không có ghi chú thêm.'}
              </div>
            </div>

            {/* Quick status update buttons */}
            <div className="pt-3 border-t border-slate-100 dark:border-navy-800 flex items-center justify-between">
              <span className="text-xs font-bold text-slate-500">Đổi trạng thái:</span>
              <div className="flex gap-1.5">
                <button
                  onClick={() => handleUpdateStatus(selectedQuote.id, 'processing')}
                  className="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 hover:bg-blue-100 transition"
                >
                  Đang xử lý
                </button>
                <button
                  onClick={() => handleUpdateStatus(selectedQuote.id, 'completed')}
                  className="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 hover:bg-emerald-100 transition"
                >
                  Hoàn tất
                </button>
                <button
                  onClick={() => handleUpdateStatus(selectedQuote.id, 'canceled')}
                  className="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 hover:bg-rose-100 transition"
                >
                  Hủy
                </button>
              </div>
            </div>
          </div>
        )}
      </Modal>

      {/* Delete Confirmation */}
      <ConfirmModal
        isOpen={Boolean(deleteTarget)}
        onClose={() => setDeleteTarget(null)}
        onConfirm={handleDelete}
        title="Xóa Báo Giá"
        message={`Bạn có chắc muốn xóa yêu cầu báo giá của "${deleteTarget?.fullname}"?`}
        confirmText="Xác nhận xóa"
        isDangerous
        isLoading={isSubmitting}
      />
    </div>
  );
};
