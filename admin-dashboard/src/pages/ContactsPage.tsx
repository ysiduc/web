import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import type { Contact, ApiResponse } from '../types';
import { Badge } from '../components/common/Badge';
import { Modal } from '../components/common/Modal';
import { ConfirmModal } from '../components/common/ConfirmModal';
import {
  Search,
  Trash2,
  Phone,
  Mail,
  Eye,
} from 'lucide-react';

export const ContactsPage: React.FC = () => {
  const [contacts, setContacts] = useState<Contact[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [status, setStatus] = useState('');

  // Modals
  const [selectedContact, setSelectedContact] = useState<Contact | null>(null);
  const [deleteTarget, setDeleteTarget] = useState<Contact | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  const fetchContacts = async () => {
    setIsLoading(true);
    try {
      const res = await api.get<ApiResponse<{ contacts: Contact[] }>>('/contacts/index.php', {
        params: { search, status },
      });
      if (res.data.success) {
        setContacts(res.data.data.contacts);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchContacts();
  }, [search, status]);

  const handleUpdateStatus = async (id: number, nextStatus: Contact['status']) => {
    try {
      const res = await api.post('/contacts/update_status.php', { id, status: nextStatus });
      if (res.data.success) {
        setContacts((prev) =>
          prev.map((c) => (c.id === id ? { ...c, status: nextStatus } : c))
        );
        if (selectedContact && selectedContact.id === id) {
          setSelectedContact({ ...selectedContact, status: nextStatus });
        }
        setFeedback({
          type: 'success',
          message: `Đã cập nhật trạng thái liên hệ #${id}!`,
        });
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể cập nhật trạng thái.',
      });
    }
  };

  const handleOpenDetail = (c: Contact) => {
    setSelectedContact(c);
    if (c.status === 'unread') {
      handleUpdateStatus(c.id, 'read');
    }
  };

  const handleDelete = async () => {
    if (!deleteTarget) return;
    setIsSubmitting(true);
    try {
      const res = await api.post('/contacts/delete.php', { id: deleteTarget.id });
      if (res.data.success) {
        setFeedback({ type: 'success', message: 'Đã xóa tin nhắn liên hệ thành công!' });
        setDeleteTarget(null);
        if (selectedContact?.id === deleteTarget.id) setSelectedContact(null);
        fetchContacts();
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể xóa tin nhắn.',
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
            Quản Lý Khách Hàng Liên Hệ
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">
            Hộp thư tiếp nhận yêu cầu tư vấn, đóng góp ý kiến &amp; kết nối đối tác
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
            placeholder="Tìm theo tên khách, email, số điện thoại, tiêu đề..."
            className="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-navy-700 bg-slate-50 dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <div className="flex flex-wrap items-center gap-1.5">
          {[
            { val: '', label: 'Tất cả' },
            { val: 'unread', label: 'Chưa đọc' },
            { val: 'read', label: 'Đã xem' },
            { val: 'replied', label: 'Đã phản hồi' },
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

      {/* Table */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm overflow-hidden">
        {isLoading ? (
          <div className="p-12 text-center">
            <div className="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
            <div className="mt-2 text-sm text-slate-400">Đang tải danh sách liên hệ...</div>
          </div>
        ) : contacts.length === 0 ? (
          <div className="p-12 text-center text-slate-400 text-sm">
            Chưa có tin nhắn liên hệ nào.
          </div>
        ) : (
          <>
            {/* Mobile Card List (< 640px) */}
            <div className="sm:hidden divide-y divide-slate-100 dark:divide-navy-800">
              {contacts.map((c) => (
                <div
                  key={c.id}
                  className={`p-4 space-y-3 cursor-pointer transition ${
                    c.status === 'unread'
                      ? 'bg-amber-50/40 dark:bg-amber-950/20'
                      : 'hover:bg-slate-50/50 dark:hover:bg-navy-800/20'
                  }`}
                  onClick={() => handleOpenDetail(c)}
                >
                  <div className="flex items-start justify-between gap-2">
                    <div>
                      <div className="flex items-center gap-2">
                        <h4 className="font-bold text-slate-900 dark:text-white text-base">
                          {c.name}
                        </h4>
                        {c.status === 'unread' && (
                          <span className="w-2 h-2 rounded-full bg-amber-500 animate-pulse" />
                        )}
                      </div>
                      <div className="text-xs text-slate-400 mt-0.5">
                        {new Date(c.created_at).toLocaleDateString('vi-VN')} · ID #{c.id}
                      </div>
                    </div>

                    <div onClick={(e) => e.stopPropagation()}>
                      <select
                        value={c.status}
                        onChange={(e) => handleUpdateStatus(c.id, e.target.value as any)}
                        className="px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-navy-700 bg-white dark:bg-navy-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-amber-500 min-h-[36px]"
                      >
                        <option value="unread">Chưa đọc</option>
                        <option value="read">Đã xem</option>
                        <option value="replied">Đã phản hồi</option>
                      </select>
                    </div>
                  </div>

                  <div className="p-2.5 rounded-xl bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-navy-800 text-xs space-y-1.5" onClick={(e) => e.stopPropagation()}>
                    <div className="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                      <Mail className="w-3.5 h-3.5 text-blue-500 flex-shrink-0" />
                      <a href={`mailto:${c.email}`} className="hover:underline truncate">
                        {c.email}
                      </a>
                    </div>
                    {c.phone && (
                      <div className="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                        <Phone className="w-3.5 h-3.5 flex-shrink-0 text-amber-500" />
                        <a href={`tel:${c.phone}`} className="font-mono hover:underline">
                          {c.phone}
                        </a>
                      </div>
                    )}
                  </div>

                  <div className="text-xs">
                    <strong className="text-slate-800 dark:text-slate-200 block mb-0.5">
                      {c.subject || 'Tư vấn dịch vụ'}
                    </strong>
                    <p className="text-slate-600 dark:text-slate-400 line-clamp-2">
                      {c.message}
                    </p>
                  </div>

                  <div className="flex items-center justify-end gap-1 pt-1" onClick={(e) => e.stopPropagation()}>
                    <button
                      onClick={() => handleOpenDetail(c)}
                      className="p-2.5 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition min-w-[44px] min-h-[44px] flex items-center justify-center"
                      title="Xem thư"
                    >
                      <Eye className="w-4 h-4" />
                    </button>
                    <button
                      onClick={() => setDeleteTarget(c)}
                      className="p-2.5 text-rose-600 hover:text-rose-800 dark:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition min-w-[44px] min-h-[44px] flex items-center justify-center"
                      title="Xóa liên hệ"
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
                    <th className="px-6 py-4">Khách hàng</th>
                    <th className="px-4 py-4">Liên hệ</th>
                    <th className="px-4 py-4">Tiêu đề &amp; Nội dung</th>
                    <th className="px-4 py-4">Trạng thái</th>
                    <th className="px-4 py-4">Ngày gửi</th>
                    <th className="px-6 py-4 text-right">Thao tác</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-navy-800">
                  {contacts.map((c) => (
                    <tr
                      key={c.id}
                      className={`hover:bg-slate-50/80 dark:hover:bg-navy-800/40 transition cursor-pointer ${
                        c.status === 'unread' ? 'bg-amber-50/30 dark:bg-amber-950/10 font-medium' : ''
                      }`}
                      onClick={() => handleOpenDetail(c)}
                    >
                      <td className="px-6 py-4">
                        <div className="font-bold text-slate-900 dark:text-white">
                          {c.name}
                        </div>
                        <div className="text-xs text-slate-400">
                          ID #{c.id}
                        </div>
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap text-xs" onClick={(e) => e.stopPropagation()}>
                        <div className="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                          <Mail className="w-3.5 h-3.5 text-blue-500 flex-shrink-0" />
                          <a href={`mailto:${c.email}`} className="hover:underline truncate max-w-[160px]">
                            {c.email}
                          </a>
                        </div>
                        {c.phone && (
                          <div className="flex items-center gap-1.5 text-slate-500 dark:text-slate-400 mt-1">
                            <Phone className="w-3.5 h-3.5 flex-shrink-0" />
                            <a href={`tel:${c.phone}`} className="hover:underline">
                              {c.phone}
                            </a>
                          </div>
                        )}
                      </td>
                      <td className="px-4 py-4 max-w-md">
                        <div className="font-semibold text-slate-800 dark:text-slate-200 text-xs truncate">
                          {c.subject || 'Tư vấn dịch vụ'}
                        </div>
                        <div className="text-slate-400 text-xs line-clamp-1 mt-0.5">
                          {c.message}
                        </div>
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap" onClick={(e) => e.stopPropagation()}>
                        <select
                          value={c.status}
                          onChange={(e) => handleUpdateStatus(c.id, e.target.value as any)}
                          className="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-200 dark:border-navy-700 bg-white dark:bg-navy-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                          <option value="unread">Chưa đọc</option>
                          <option value="read">Đã xem</option>
                          <option value="replied">Đã phản hồi</option>
                        </select>
                      </td>
                      <td className="px-4 py-4 whitespace-nowrap text-xs text-slate-400">
                        {new Date(c.created_at).toLocaleDateString('vi-VN')}
                      </td>
                      <td className="px-6 py-4 text-right whitespace-nowrap" onClick={(e) => e.stopPropagation()}>
                        <div className="flex items-center justify-end gap-1.5">
                          <button
                            onClick={() => handleOpenDetail(c)}
                            className="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
                            title="Xem thư"
                          >
                            <Eye className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => setDeleteTarget(c)}
                            className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition"
                            title="Xóa liên hệ"
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

      {/* Detail Modal */}
      <Modal
        isOpen={Boolean(selectedContact)}
        onClose={() => setSelectedContact(null)}
        title={`Chi Tiết Thư Liên Hệ #${selectedContact?.id}`}
        maxWidth="lg"
      >
        {selectedContact && (
          <div className="space-y-4">
            <div className="p-4 rounded-2xl bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-navy-800 space-y-3">
              <div className="flex items-center justify-between">
                <div>
                  <div className="text-xs text-slate-400">Khách hàng liên hệ</div>
                  <div className="text-base font-bold text-slate-900 dark:text-white">
                    {selectedContact.name}
                  </div>
                </div>
                <Badge status={selectedContact.status} />
              </div>

              <div className="grid grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-200 dark:border-navy-800">
                <div>
                  <div className="text-slate-400 mb-0.5">Email:</div>
                  <a
                    href={`mailto:${selectedContact.email}`}
                    className="font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1"
                  >
                    <Mail className="w-3.5 h-3.5" />
                    {selectedContact.email}
                  </a>
                </div>
                <div>
                  <div className="text-slate-400 mb-0.5">Số điện thoại:</div>
                  <a
                    href={`tel:${selectedContact.phone}`}
                    className="font-medium text-slate-700 dark:text-slate-300 hover:underline"
                  >
                    {selectedContact.phone || 'Chưa cung cấp'}
                  </a>
                </div>
              </div>
            </div>

            <div className="text-xs">
              <span className="font-bold text-slate-700 dark:text-slate-300">Tiêu đề: </span>
              <span className="text-slate-900 dark:text-white font-semibold">{selectedContact.subject || 'Liên hệ chung'}</span>
            </div>

            <div>
              <div className="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Nội dung thư:
              </div>
              <div className="p-4 rounded-xl bg-slate-100 dark:bg-navy-950 border border-slate-200 dark:border-navy-800 text-xs text-slate-800 dark:text-slate-200 whitespace-pre-wrap leading-relaxed">
                {selectedContact.message}
              </div>
            </div>

            {/* Quick reply and status update */}
            <div className="pt-3 border-t border-slate-100 dark:border-navy-800 flex items-center justify-between">
              <a
                href={`mailto:${selectedContact.email}?subject=Phản hồi từ PNMEC: ${encodeURIComponent(selectedContact.subject || '')}`}
                className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 transition"
              >
                <Mail className="w-3.5 h-3.5" />
                <span>Gửi Email Trả Lời</span>
              </a>

              <div className="flex gap-1.5">
                <button
                  onClick={() => handleUpdateStatus(selectedContact.id, 'replied')}
                  className="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 hover:bg-emerald-100 transition"
                >
                  Đánh dấu Đã phản hồi
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
        title="Xóa Liên Hệ"
        message={`Bạn có chắc muốn xóa tin nhắn từ "${deleteTarget?.name}"?`}
        confirmText="Xác nhận xóa"
        isDangerous
        isLoading={isSubmitting}
      />
    </div>
  );
};
