import React, { useEffect, useState } from 'react';
import { api, getImageUrl, getPublicPageUrl } from '../api/client';
import type { DashboardStats, ApiResponse } from '../types';
import { Badge } from '../components/common/Badge';
import {
  Building2,
  Wrench,
  Newspaper,
  Calculator,
  MessageSquare,
  Users,
  ArrowUpRight,
  PlusCircle,
  HardHat,
  TrendingUp,
} from 'lucide-react';
import {
  ResponsiveContainer,
  AreaChart,
  Area,
  XAxis,
  YAxis,
  Tooltip,
  CartesianGrid,
} from 'recharts';

export const DashboardPage: React.FC = () => {
  const [data, setData] = useState<DashboardStats | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  const fetchStats = async () => {
    try {
      const res = await api.get<ApiResponse<DashboardStats>>('/dashboard/index.php');
      if (res.data.success) {
        setData(res.data.data);
      }
    } catch (e) {
      console.error('Failed to load dashboard statistics', e);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchStats();
  }, []);

  if (isLoading) {
    return (
      <div className="flex h-96 items-center justify-center">
        <div className="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
      </div>
    );
  }

  const stats = data?.stats;

  return (
    <div className="space-y-6">
      {/* Welcome Banner */}
      <div className="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-r from-navy-900 via-navy-800 to-slate-900 text-white p-5 sm:p-8 shadow-xl border border-navy-700/50">
        <div className="relative z-10 max-w-2xl">
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider mb-3">
            <HardHat className="w-3.5 h-3.5" />
            Hệ Thống Quản Trị Trung Tâm PNMEC
          </div>
          <h2 className="text-xl sm:text-3xl font-extrabold tracking-tight font-heading leading-tight">
            Xin chào! Chúc một ngày làm việc hiệu quả.
          </h2>
          <p className="mt-2 text-slate-300 text-xs sm:text-base leading-relaxed">
            Hệ thống hiển thị dữ liệu thực tế từ cơ sở dữ liệu PNMEC. Bạn có thể theo dõi yêu cầu báo giá, liên hệ khách hàng và quản lý toàn bộ nội dung dịch vụ, công trình.
          </p>
          <div className="mt-5 flex flex-wrap gap-2.5 sm:gap-3">
            <a
              href="#/projects"
              className="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition min-h-[44px]"
            >
              <PlusCircle className="w-4 h-4" />
              <span>Quản lý Công Trình</span>
            </a>
            <a
              href={getPublicPageUrl('/index.php')}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur transition min-h-[44px]"
            >
              <span>Xem Website Ngoài</span>
              <ArrowUpRight className="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>

      {/* Top 6 KPI Stat Cards */}
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        {/* Projects */}
        <div className="bg-white dark:bg-navy-900 p-3.5 sm:p-5 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm hover:shadow-md transition">
          <div className="flex items-center justify-between">
            <div className="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center">
              <Building2 className="w-4 h-4 sm:w-5 sm:h-5" />
            </div>
            <span className="text-[10px] sm:text-[11px] font-semibold text-slate-400 uppercase">Dự án</span>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 dark:text-white">
              {stats?.projects.total ?? 0}
            </div>
            <div className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
              {stats?.projects.co_khi ?? 0} Cơ khí • {stats?.projects.xay_dung ?? 0} Xây dựng
            </div>
          </div>
        </div>

        {/* Services */}
        <div className="bg-white dark:bg-navy-900 p-5 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm hover:shadow-md transition">
          <div className="flex items-center justify-between">
            <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
              <Wrench className="w-5 h-5" />
            </div>
            <span className="text-[11px] font-semibold text-slate-400 uppercase">Dịch vụ</span>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 dark:text-white">
              {stats?.services.total ?? 0}
            </div>
            <div className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
              {stats?.services.featured ?? 0} Hạng mục nổi bật
            </div>
          </div>
        </div>

        {/* Quotes */}
        <div className="bg-white dark:bg-navy-900 p-5 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm hover:shadow-md transition">
          <div className="flex items-center justify-between">
            <div className="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
              <Calculator className="w-5 h-5" />
            </div>
            <span className="text-[11px] font-semibold text-slate-400 uppercase">Báo giá</span>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 dark:text-white">
              {stats?.quotes.total ?? 0}
            </div>
            <div className="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-0.5">
              {stats?.quotes.new ?? 0} Yêu cầu mới
            </div>
          </div>
        </div>

        {/* Contacts */}
        <div className="bg-white dark:bg-navy-900 p-5 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm hover:shadow-md transition">
          <div className="flex items-center justify-between">
            <div className="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center">
              <MessageSquare className="w-5 h-5" />
            </div>
            <span className="text-[11px] font-semibold text-slate-400 uppercase">Liên hệ</span>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 dark:text-white">
              {stats?.contacts.total ?? 0}
            </div>
            <div className="text-xs text-rose-500 font-semibold mt-0.5">
              {stats?.contacts.unread ?? 0} Tin chưa đọc
            </div>
          </div>
        </div>

        {/* News */}
        <div className="bg-white dark:bg-navy-900 p-5 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm hover:shadow-md transition">
          <div className="flex items-center justify-between">
            <div className="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-950/50 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
              <Newspaper className="w-5 h-5" />
            </div>
            <span className="text-[11px] font-semibold text-slate-400 uppercase">Tin tức</span>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 dark:text-white">
              {stats?.news.total ?? 0}
            </div>
            <div className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
              Bài viết kỹ thuật
            </div>
          </div>
        </div>

        {/* Users */}
        <div className="bg-white dark:bg-navy-900 p-5 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm hover:shadow-md transition">
          <div className="flex items-center justify-between">
            <div className="w-10 h-10 rounded-xl bg-slate-100 dark:bg-navy-800 text-slate-700 dark:text-slate-300 flex items-center justify-center">
              <Users className="w-5 h-5" />
            </div>
            <span className="text-[11px] font-semibold text-slate-400 uppercase">Nhân sự</span>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 dark:text-white">
              {stats?.users.total ?? 0}
            </div>
            <div className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
              Tài khoản quản trị
            </div>
          </div>
        </div>
      </div>

      {/* Monthly Chart */}
      <div className="bg-white dark:bg-navy-900 p-6 rounded-3xl border border-slate-200 dark:border-navy-800 shadow-sm">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h3 className="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <TrendingUp className="w-5 h-5 text-amber-500" />
              Biểu Đồ Xu Hướng Yêu Cầu &amp; Tương Tác (6 Tháng)
            </h3>
            <p className="text-xs text-slate-500 dark:text-slate-400">
              Số lượng Yêu cầu Báo Giá &amp; Liên hệ khách hàng theo tháng
            </p>
          </div>
          <div className="flex items-center gap-4 text-xs font-semibold">
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-amber-500" />
              <span className="text-slate-600 dark:text-slate-300">Báo Giá</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-full bg-blue-500" />
              <span className="text-slate-600 dark:text-slate-300">Liên Hệ</span>
            </div>
          </div>
        </div>

        <div className="h-64 w-full">
          <ResponsiveContainer width="100%" height="100%">
            <AreaChart data={data?.monthly_trends || []}>
              <defs>
                <linearGradient id="colorQuotes" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#f59e0b" stopOpacity={0.4} />
                  <stop offset="95%" stopColor="#f59e0b" stopOpacity={0.0} />
                </linearGradient>
                <linearGradient id="colorContacts" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.4} />
                  <stop offset="95%" stopColor="#3b82f6" stopOpacity={0.0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#94a3b8" opacity={0.15} />
              <XAxis dataKey="month" stroke="#94a3b8" fontSize={12} tickLine={false} />
              <YAxis stroke="#94a3b8" fontSize={12} tickLine={false} allowDecimals={false} />
              <Tooltip
                contentStyle={{
                  backgroundColor: '#0f172a',
                  borderColor: '#1e293b',
                  borderRadius: '12px',
                  color: '#fff',
                  fontSize: '12px',
                }}
              />
              <Area
                type="monotone"
                dataKey="quotes"
                name="Báo giá"
                stroke="#f59e0b"
                strokeWidth={2.5}
                fillOpacity={1}
                fill="url(#colorQuotes)"
              />
              <Area
                type="monotone"
                dataKey="contacts"
                name="Liên hệ"
                stroke="#3b82f6"
                strokeWidth={2.5}
                fillOpacity={1}
                fill="url(#colorContacts)"
              />
            </AreaChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Recent Quotes & Recent Contacts Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent Quotes */}
        <div className="bg-white dark:bg-navy-900 rounded-3xl p-6 border border-slate-200 dark:border-navy-800 shadow-sm flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <Calculator className="w-5 h-5 text-amber-500" />
                Yêu Cầu Báo Giá Gần Đây
              </h3>
              <a
                href="#/quotes"
                className="text-xs font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 flex items-center gap-1"
              >
                <span>Xem tất cả</span>
                <ArrowUpRight className="w-3.5 h-3.5" />
              </a>
            </div>

            <div className="divide-y divide-slate-100 dark:divide-navy-800">
              {data?.recent_quotes?.length ? (
                data.recent_quotes.map((q) => (
                  <div key={q.id} className="py-3 flex items-center justify-between gap-4">
                    <div className="min-w-0">
                      <div className="font-semibold text-sm text-slate-900 dark:text-white truncate">
                        {q.fullname}
                      </div>
                      <div className="text-xs text-slate-500 dark:text-slate-400 truncate">
                        {q.phone} • {q.service_type || 'Tư vấn tổng quát'}
                      </div>
                    </div>
                    <div className="flex items-center gap-2">
                      <Badge status={q.status} />
                    </div>
                  </div>
                ))
              ) : (
                <div className="py-8 text-center text-sm text-slate-400">
                  Chưa có yêu cầu báo giá nào.
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Recent Contacts */}
        <div className="bg-white dark:bg-navy-900 rounded-3xl p-6 border border-slate-200 dark:border-navy-800 shadow-sm flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-4">
              <h3 className="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <MessageSquare className="w-5 h-5 text-blue-500" />
                Tin Nhắn Liên Hệ Mới Nhất
              </h3>
              <a
                href="#/contacts"
                className="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1"
              >
                <span>Xem tất cả</span>
                <ArrowUpRight className="w-3.5 h-3.5" />
              </a>
            </div>

            <div className="divide-y divide-slate-100 dark:divide-navy-800">
              {data?.recent_contacts?.length ? (
                data.recent_contacts.map((c) => (
                  <div key={c.id} className="py-3 flex items-center justify-between gap-4">
                    <div className="min-w-0">
                      <div className="font-semibold text-sm text-slate-900 dark:text-white truncate">
                        {c.name}
                      </div>
                      <div className="text-xs text-slate-500 dark:text-slate-400 truncate">
                        {c.email} • {c.subject || 'Liên hệ tư vấn'}
                      </div>
                    </div>
                    <div className="flex items-center gap-2">
                      <Badge status={c.status} />
                    </div>
                  </div>
                ))
              ) : (
                <div className="py-8 text-center text-sm text-slate-400">
                  Chưa có tin nhắn liên hệ nào.
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Recent Projects Showcase */}
      <div className="bg-white dark:bg-navy-900 rounded-3xl p-6 border border-slate-200 dark:border-navy-800 shadow-sm">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <Building2 className="w-5 h-5 text-amber-500" />
            Công Trình &amp; Dự Án Cập Nhật Gần Đây
          </h3>
          <a
            href="#/projects"
            className="text-xs font-semibold text-amber-600 hover:text-amber-700 dark:text-amber-400 flex items-center gap-1"
          >
            <span>Quản lý tất cả</span>
            <ArrowUpRight className="w-3.5 h-3.5" />
          </a>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          {data?.recent_projects?.map((p) => (
            <div
              key={p.id}
              className="group rounded-2xl border border-slate-200 dark:border-navy-800 bg-slate-50 dark:bg-navy-950 overflow-hidden flex flex-col hover:border-amber-400 dark:hover:border-amber-500 transition"
            >
              <div className="h-32 bg-slate-200 dark:bg-navy-800 relative overflow-hidden">
                <img
                  src={getImageUrl(p.image)}
                  alt={p.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                  onError={(e) => {
                    (e.target as HTMLElement).style.display = 'none';
                  }}
                />
                <div className="absolute top-2 right-2">
                  <Badge status={p.status} />
                </div>
              </div>
              <div className="p-3.5 flex-1 flex flex-col justify-between">
                <div>
                  <span className="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">
                    {p.category}
                  </span>
                  <h4 className="text-xs font-bold text-slate-900 dark:text-white line-clamp-2 mt-1 leading-snug">
                    {p.title}
                  </h4>
                </div>
                <div className="mt-3 pt-2 border-t border-slate-200 dark:border-navy-800 text-[11px] text-slate-400 flex items-center justify-between">
                  <span className="truncate">{p.location || 'Hà Nội'}</span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};
