import React, { useEffect, useState } from 'react';
import { api } from '../api/client';
import type { User, ApiResponse } from '../types';
import { useAuthStore } from '../store/authStore';
import { Badge } from '../components/common/Badge';
import { Modal } from '../components/common/Modal';
import { ConfirmModal } from '../components/common/ConfirmModal';
import {
  UserPlus,
  Edit2,
  Trash2,
} from 'lucide-react';

export const UsersPage: React.FC = () => {
  const { user: currentUser } = useAuthStore();
  const [users, setUsers] = useState<User[]>([]);
  const [isLoading, setIsLoading] = useState(true);

  // Modals
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<User | null>(null);
  const [deleteTarget, setDeleteTarget] = useState<User | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  // Form
  const [formUsername, setFormUsername] = useState('');
  const [formPassword, setFormPassword] = useState('');
  const [formFullname, setFormFullname] = useState('');
  const [formEmail, setFormEmail] = useState('');
  const [formPhone, setFormPhone] = useState('');
  const [formRole, setFormRole] = useState<'admin' | 'staff'>('staff');
  const [formStatus, setFormStatus] = useState<'active' | 'inactive'>('active');

  const fetchUsers = async () => {
    setIsLoading(true);
    try {
      const res = await api.get<ApiResponse<{ users: User[] }>>('/users/index.php');
      if (res.data.success) {
        setUsers(res.data.data.users);
      }
    } catch (e: any) {
      setFeedback({
        type: 'error',
        message: e.response?.data?.message || 'Không thể tải danh sách tài khoản.',
      });
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers();
  }, []);

  const handleOpenCreate = () => {
    setEditingUser(null);
    setFormUsername('');
    setFormPassword('');
    setFormFullname('');
    setFormEmail('');
    setFormPhone('');
    setFormRole('staff');
    setFormStatus('active');
    setIsModalOpen(true);
  };

  const handleOpenEdit = (u: User) => {
    setEditingUser(u);
    setFormUsername(u.username);
    setFormPassword('');
    setFormFullname(u.fullname);
    setFormEmail(u.email);
    setFormPhone(u.phone || '');
    setFormRole(u.role);
    setFormStatus(u.status);
    setIsModalOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    setFeedback(null);

    try {
      if (editingUser) {
        const payload: any = {
          id: editingUser.id,
          fullname: formFullname.trim(),
          email: formEmail.trim(),
          phone: formPhone.trim(),
          role: formRole,
          status: formStatus,
        };
        if (formPassword.trim()) {
          payload.password = formPassword.trim();
        }

        const res = await api.post('/users/update.php', payload);
        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Cập nhật tài khoản thành công!' });
          setIsModalOpen(false);
          fetchUsers();
        }
      } else {
        if (!formUsername.trim() || !formPassword.trim()) {
          setFeedback({ type: 'error', message: 'Vui lòng nhập tên đăng nhập và mật khẩu.' });
          setIsSubmitting(false);
          return;
        }

        const res = await api.post('/users/create.php', {
          username: formUsername.trim(),
          password: formPassword.trim(),
          fullname: formFullname.trim(),
          email: formEmail.trim(),
          phone: formPhone.trim(),
          role: formRole,
          status: formStatus,
        });

        if (res.data.success) {
          setFeedback({ type: 'success', message: 'Tạo tài khoản nhân viên mới thành công!' });
          setIsModalOpen(false);
          fetchUsers();
        }
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Có lỗi xảy ra khi lưu tài khoản.',
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleDelete = async () => {
    if (!deleteTarget) return;
    setIsSubmitting(true);
    try {
      const res = await api.post('/users/delete.php', { id: deleteTarget.id });
      if (res.data.success) {
        setFeedback({ type: 'success', message: 'Đã xóa tài khoản thành công!' });
        setDeleteTarget(null);
        fetchUsers();
      }
    } catch (err: any) {
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Không thể xóa tài khoản.',
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
          <h2 className="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <span>Quản Lý Tài Khoản Nhân Viên</span>
            <span className="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border border-amber-300 dark:border-amber-800">
              Admin Only
            </span>
          </h2>
          <p className="text-sm text-slate-500 dark:text-slate-400">
            Phân quyền quản trị hệ thống (Admin) và nhân viên phụ trách nội dung kỹ thuật (Staff)
          </p>
        </div>
        <button
          onClick={handleOpenCreate}
          className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition self-start sm:self-auto"
        >
          <UserPlus className="w-4 h-4" />
          <span>Thêm Tài Khoản Mới</span>
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

      {/* Users Table */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm overflow-hidden">
        {isLoading ? (
          <div className="p-12 text-center">
            <div className="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
            <div className="mt-2 text-sm text-slate-400">Đang tải danh sách tài khoản...</div>
          </div>
        ) : (
          <>
            {/* Mobile Card List (< 640px) */}
            <div className="sm:hidden divide-y divide-slate-100 dark:divide-navy-800">
              {users.map((u) => {
                const isSelf = u.id === currentUser?.id;
                return (
                  <div key={u.id} className="p-4 space-y-3">
                    <div className="flex items-start justify-between gap-3">
                      <div className="flex items-center gap-3">
                        <div className="w-11 h-11 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-300 text-navy-950 font-bold flex items-center justify-center text-base shadow-sm flex-shrink-0">
                          {u.fullname ? u.fullname.charAt(0).toUpperCase() : u.username.charAt(0).toUpperCase()}
                        </div>
                        <div>
                          <div className="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span>{u.fullname}</span>
                            {isSelf && (
                              <span className="text-[10px] px-1.5 py-0.2 rounded bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 font-bold">
                                Bạn
                              </span>
                            )}
                          </div>
                          <div className="text-xs text-slate-400 font-mono">
                            @{u.username}
                          </div>
                        </div>
                      </div>

                      <div className="flex items-center gap-1.5">
                        <Badge status={u.role} />
                        <Badge status={u.status} />
                      </div>
                    </div>

                    <div className="p-2.5 rounded-xl bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-navy-800 text-xs space-y-1 font-mono text-slate-600 dark:text-slate-300">
                      <div>Email: <strong className="text-slate-900 dark:text-white font-normal">{u.email}</strong></div>
                      {u.phone && <div>SĐT: <strong className="text-slate-900 dark:text-white font-normal">{u.phone}</strong></div>}
                    </div>

                    <div className="flex items-center justify-end gap-1 pt-1">
                      <button
                        onClick={() => handleOpenEdit(u)}
                        className="p-2.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition min-w-[44px] min-h-[44px] flex items-center justify-center"
                        title="Sửa tài khoản / Đổi mật khẩu"
                      >
                        <Edit2 className="w-4 h-4" />
                      </button>
                      {!isSelf && (
                        <button
                          onClick={() => setDeleteTarget(u)}
                          className="p-2.5 text-rose-600 hover:text-rose-800 dark:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition min-w-[44px] min-h-[44px] flex items-center justify-center"
                          title="Xóa tài khoản"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      )}
                    </div>
                  </div>
                );
              })}
            </div>

            {/* Desktop Table (>= 640px) */}
            <div className="hidden sm:block overflow-x-auto">
              <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead className="bg-slate-50 dark:bg-navy-950 text-xs uppercase font-bold text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-navy-800">
                  <tr>
                    <th className="px-6 py-4">Tài khoản &amp; Họ tên</th>
                    <th className="px-4 py-4">Email</th>
                    <th className="px-4 py-4">Số điện thoại</th>
                    <th className="px-4 py-4">Vai trò</th>
                    <th className="px-4 py-4">Trạng thái</th>
                    <th className="px-6 py-4 text-right">Thao tác</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-navy-800">
                  {users.map((u) => {
                    const isSelf = u.id === currentUser?.id;
                    return (
                      <tr
                        key={u.id}
                        className="hover:bg-slate-50/80 dark:hover:bg-navy-800/40 transition"
                      >
                        <td className="px-6 py-4">
                          <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-300 text-navy-950 font-bold flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                              {u.fullname ? u.fullname.charAt(0).toUpperCase() : u.username.charAt(0).toUpperCase()}
                            </div>
                            <div>
                              <div className="font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span>{u.fullname}</span>
                                {isSelf && (
                                  <span className="text-[10px] px-1.5 py-0.2 rounded bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 font-bold">
                                    Bạn
                                  </span>
                                )}
                              </div>
                              <div className="text-xs text-slate-400 font-mono">
                                @{u.username}
                              </div>
                            </div>
                          </div>
                        </td>
                        <td className="px-4 py-4 text-xs font-mono text-slate-600 dark:text-slate-300">
                          {u.email}
                        </td>
                        <td className="px-4 py-4 text-xs font-mono text-slate-600 dark:text-slate-300">
                          {u.phone || '—'}
                        </td>
                        <td className="px-4 py-4 whitespace-nowrap">
                          <Badge status={u.role} />
                        </td>
                        <td className="px-4 py-4 whitespace-nowrap">
                          <Badge status={u.status} />
                        </td>
                        <td className="px-6 py-4 text-right whitespace-nowrap">
                          <div className="flex items-center justify-end gap-1.5">
                            <button
                              onClick={() => handleOpenEdit(u)}
                              className="p-2 text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/50 transition"
                              title="Sửa tài khoản / Đổi mật khẩu"
                            >
                              <Edit2 className="w-4 h-4" />
                            </button>
                            {!isSelf && (
                              <button
                                onClick={() => setDeleteTarget(u)}
                                className="p-2 text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/50 transition"
                                title="Xóa tài khoản"
                              >
                                <Trash2 className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </td>
                      </tr>
                    );
                  })}
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
        title={editingUser ? `Chỉnh Sửa Tài Khoản: @${editingUser.username}` : 'Thêm Tài Khoản Nhân Viên Mới'}
        maxWidth="lg"
      >
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Tên đăng nhập *
              </label>
              <input
                type="text"
                required
                disabled={Boolean(editingUser)}
                value={formUsername}
                onChange={(e) => setFormUsername(e.target.value)}
                placeholder="VD: kysu_nam"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm disabled:opacity-50"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                {editingUser ? 'Mật khẩu mới (Để trống nếu không đổi)' : 'Mật khẩu khởi tạo *'}
              </label>
              <input
                type="password"
                required={!editingUser}
                value={formPassword}
                onChange={(e) => setFormPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div className="sm:col-span-2">
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Họ và tên đầy đủ *
              </label>
              <input
                type="text"
                required
                value={formFullname}
                onChange={(e) => setFormFullname(e.target.value)}
                placeholder="VD: Nguyễn Văn Kỹ Sư"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Địa chỉ Email
              </label>
              <input
                type="email"
                value={formEmail}
                onChange={(e) => setFormEmail(e.target.value)}
                placeholder="email@pnmec.vn"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Số điện thoại
              </label>
              <input
                type="tel"
                value={formPhone}
                onChange={(e) => setFormPhone(e.target.value)}
                placeholder="0987.xxx.xxx"
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              />
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Phân quyền tài khoản
              </label>
              <select
                value={formRole}
                onChange={(e) => setFormRole(e.target.value as any)}
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              >
                <option value="staff">Nhân viên / Kỹ sư (Staff)</option>
                <option value="admin">Quản trị viên (Admin Toàn Quyền)</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                Trạng thái tài khoản
              </label>
              <select
                value={formStatus}
                onChange={(e) => setFormStatus(e.target.value as any)}
                className="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
              >
                <option value="active">Hoạt động (Được phép đăng nhập)</option>
                <option value="inactive">Khóa (Vô hiệu hóa đăng nhập)</option>
              </select>
            </div>
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
              {isSubmitting ? 'Đang lưu...' : editingUser ? 'Lưu Thông Tin' : 'Tạo Tài Khoản'}
            </button>
          </div>
        </form>
      </Modal>

      {/* Delete Confirmation */}
      <ConfirmModal
        isOpen={Boolean(deleteTarget)}
        onClose={() => setDeleteTarget(null)}
        onConfirm={handleDelete}
        title="Xóa Tài Khoản Nhân Viên"
        message={`Bạn có chắc muốn xóa tài khoản "${deleteTarget?.fullname}" (@${deleteTarget?.username})?`}
        confirmText="Xác nhận xóa"
        isDangerous
        isLoading={isSubmitting}
      />
    </div>
  );
};
