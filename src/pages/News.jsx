import React from "react";
import { motion } from "framer-motion";
import { useQuery } from "@tanstack/react-query";
import { base44 } from "@/api/base44Client";
import { Calendar, ArrowRight, Newspaper } from "lucide-react";
import { format } from "date-fns";
import { Skeleton } from "@/components/ui/skeleton";

export default function News() {
  const { data: articles = [], isLoading } = useQuery({
    queryKey: ['news'],
    queryFn: () => base44.entities.NewsArticle.filter({ published: true }, '-created_date', 20),
    initialData: []
  });

  // Default news items if database is empty
  const defaultNews = [
    {
      id: 1,
      title: "RETI Launches New 2025/2026 Training Programs",
      excerpt: "We are excited to announce the launch of our comprehensive training programs for the upcoming academic year, featuring new courses in construction technology and heavy equipment operation.",
      image_url: "https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&q=80",
      created_date: new Date().toISOString()
    },
    {
      id: 2,
      title: "Partnership with TEVETA for Skills Development",
      excerpt: "Rising East Training Institute strengthens its partnership with TEVETA to enhance vocational training standards and ensure our graduates receive nationally recognized certifications.",
      image_url: "https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&q=80",
      created_date: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString()
    },
    {
      id: 3,
      title: "Graduation Ceremony 2024: Celebrating Success",
      excerpt: "Over 500 students received their certificates at our annual graduation ceremony, marking the beginning of their professional careers in various industries across Zambia.",
      image_url: "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80",
      created_date: new Date(Date.now() - 14 * 24 * 60 * 60 * 1000).toISOString()
    },
    {
      id: 4,
      title: "New Heavy Equipment Training Facility",
      excerpt: "RETI opens state-of-the-art heavy equipment training facility featuring excavators, graders, and JCB machines for hands-on practical training.",
      image_url: "https://images.unsplash.com/photo-1580901368919-7738efb0f87e?w=800&q=80",
      created_date: new Date(Date.now() - 21 * 24 * 60 * 60 * 1000).toISOString()
    },
    {
      id: 5,
      title: "Industry Partnership: Construction Skills Training",
      excerpt: "Major construction companies partner with RETI to provide internship opportunities and job placements for our graduates in the construction sector.",
      image_url: "https://images.unsplash.com/photo-1504917595217-d4dc5ebb6122?w=800&q=80",
      created_date: new Date(Date.now() - 28 * 24 * 60 * 60 * 1000).toISOString()
    }
  ];

  const displayNews = articles.length > 0 ? articles : defaultNews;

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero */}
      <section className="relative h-[40vh] min-h-[300px] overflow-hidden">
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{ 
            backgroundImage: "url(https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1920&q=80)"
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/95 via-[#1e3a5f]/80 to-[#1e3a5f]/60" />
        
        <div className="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            className="text-center"
          >
            <h1 className="text-5xl md:text-6xl font-bold text-white mb-4">
              News & <span className="text-[#f5a623]">Updates</span>
            </h1>
            <p className="text-xl text-gray-300 max-w-2xl mx-auto">
              Stay updated with the latest news and events from RETI
            </p>
          </motion.div>
        </div>
      </section>

      {/* News Grid */}
      <section className="py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {isLoading ? (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {[1, 2, 3, 4, 5, 6].map((i) => (
                <div key={i} className="bg-white rounded-2xl overflow-hidden shadow-md">
                  <Skeleton className="h-48 w-full" />
                  <div className="p-6 space-y-3">
                    <Skeleton className="h-4 w-24" />
                    <Skeleton className="h-6 w-full" />
                    <Skeleton className="h-4 w-full" />
                    <Skeleton className="h-4 w-3/4" />
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {displayNews.map((article, index) => (
                <motion.article
                  key={article.id}
                  initial={{ opacity: 0, y: 30 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.1 }}
                  className="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group"
                >
                  {/* Image */}
                  <div className="relative h-48 overflow-hidden">
                    <img
                      src={article.image_url || "https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80"}
                      alt={article.title}
                      className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />
                    <div className="absolute bottom-4 left-4 flex items-center gap-2 text-white text-sm">
                      <Calendar className="w-4 h-4" />
                      {format(new Date(article.created_date), 'MMM d, yyyy')}
                    </div>
                  </div>

                  {/* Content */}
                  <div className="p-6">
                    <h3 className="text-xl font-bold text-[#1e3a5f] mb-3 group-hover:text-[#f5a623] transition-colors line-clamp-2">
                      {article.title}
                    </h3>
                    <p className="text-gray-600 line-clamp-3 mb-4">
                      {article.excerpt || article.content?.substring(0, 150)}
                    </p>
                    <button className="inline-flex items-center gap-2 text-[#1e3a5f] font-semibold hover:text-[#f5a623] transition-colors group/link">
                      Read More
                      <ArrowRight className="w-4 h-4 group-hover/link:translate-x-1 transition-transform" />
                    </button>
                  </div>
                </motion.article>
              ))}
            </div>
          )}

          {displayNews.length === 0 && !isLoading && (
            <div className="text-center py-16">
              <div className="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Newspaper className="w-10 h-10 text-gray-400" />
              </div>
              <h3 className="text-xl font-semibold text-gray-700 mb-2">No news articles yet</h3>
              <p className="text-gray-500">Check back later for updates</p>
            </div>
          )}
        </div>
      </section>
    </div>
  );
}