import { HardHat, Home } from 'lucide-react';

export const NotFoundPage: React.FC = () => {
  return (
    <div className="min-h-screen flex items-center justify-center p-6 bg-slate-50 dark:bg-navy-950 text-center">
      <div className="max-w-md w-full">
        <div className="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-4">
          <HardHat className="w-8 h-8" />
        </div>
        <h1 className="text-4xl font-extrabold text-slate-900 dark:text-white mb-2">404</h1>
        <h2 className="text-xl font-bold text-slate-800 dark:text-slate-200 mb-2">
          Không tìm thấy trang yêu cầu
        </h2>
        <p className="text-sm text-slate-500 dark:text-slate-400 mb-6">
          Đường dẫn bạn vừa truy cập không tồn tại hoặc đã được di chuyển trong hệ thống quản trị PNMEC.
        </p>
        <div className="flex justify-center gap-3">
          <a
            href="#/"
            className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition"
          >
            <Home className="w-4 h-4" />
            <span>Về Bảng Điều Khiển</span>
          </a>
        </div>
      </div>
    </div>
  );
};
