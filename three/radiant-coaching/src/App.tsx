import { useEffect, useRef, useState, type FormEvent, type ReactNode } from 'react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { ArrowDownRight, ArrowRight, Check, ChevronDown, ChevronLeft, ChevronRight, CircleCheck, Clock3, Instagram, Menu, MessageCircle, Phone, Quote, Target, X, Youtube } from 'lucide-react';
import { ErrorBoundary } from '@/components/error-boundary';
import { Toaster } from '@/components/ui/toaster';
import { TooltipProvider } from '@/components/ui/tooltip';
import NotFound from '@/pages/not-found';
import { Route, Switch, useLocation, Router as WouterRouter } from 'wouter';

const queryClient = new QueryClient();
const asset = (name: string) => `/assets/${name}`;

const programs = [
  { id: 'jee', title: 'IIT-JEE', kicker: 'Engineering, decoded', detail: 'Build the kind of problem-solving instinct that makes the toughest questions feel familiar.', image: asset('iit-jee.jpg'), duration: '2 year programme', accent: '#1ba99b', points: ['Physics, Chemistry & Mathematics', 'Weekly JEE-pattern testing', 'Rank-focused mentoring'] },
  { id: 'neet', title: 'NEET', kicker: 'Medicine starts here', detail: 'A patient, precise approach to biology and the confidence to keep showing up.', image: asset('NEET.png'), duration: '2 year programme', accent: '#ee4d2f', points: ['Physics, Chemistry & Biology', 'NCERT-first study system', 'Parent progress reviews'] },
  { id: 'cet', title: 'MHT-CET', kicker: 'Maharashtra, your moment', detail: 'Turn speed, accuracy and the right mock strategy into a college-changing score.', image: asset('mht-cet.png'), duration: '1 year programme', accent: '#ffd21e', points: ['PCM / PCB tracks', 'Real exam simulations', 'Doubt-solving every day'] },
  { id: 'foundation', title: '11th–12th', kicker: 'Start before the rush', detail: 'Strong fundamentals now. More choices later. Your future deserves a head start.', image: asset('11-12th.png'), duration: '2 year programme', accent: '#ffcf24', points: ['School + entrance balance', 'Concepts before shortcuts', 'Study rhythm that lasts'] },
  { id: 'crash', title: 'Crash Course', kicker: 'Make every day count', detail: 'A sharp, high-intensity revision sprint for students ready to close the gap.', image: asset('carsh course.png'), duration: 'Focused revision', accent: '#df124f', points: ['High-yield revision plan', 'Daily timed practice', 'Personal performance map'] },
];

const stories = [
  { name: 'Anushka Kulkarni', result: '98.6 percentile · MHT-CET', quote: 'Radiant gave me a plan I could actually follow. The teachers knew when to push and when to slow down.', initials: 'AK' },
  { name: 'Vedant Patil', result: 'IIT-JEE aspirant · 2024', quote: 'I stopped collecting notes and started solving. That shift changed everything for me.', initials: 'VP' },
  { name: 'Sara Khan', result: 'NEET aspirant · 2024', quote: 'My parents saw the progress before I did. Every test came with a next step, not just a number.', initials: 'SK' },
];

function useReveal() {
  const ref = useRef<HTMLDivElement>(null);
  useEffect(() => {
    const node = ref.current;
    if (!node) return;
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) { node.classList.add('is-visible'); observer.disconnect(); }
    }, { threshold: .12 });
    observer.observe(node);
    return () => observer.disconnect();
  }, []);
  return ref;
}

function Reveal({ children, className = '', delay = '' }: { children: ReactNode; className?: string; delay?: string }) {
  const ref = useReveal();
  return <div ref={ref} className={`reveal ${delay} ${className}`}>{children}</div>;
}

function Header({ onEnquire }: { onEnquire: () => void }) {
  const [open, setOpen] = useState(false);
  const links = [['Programs', '#programs'], ['Results', '#results'], ['Student life', '#student-life'], ['Our approach', '#about']];
  const navigate = (href: string) => { setOpen(false); document.querySelector(href)?.scrollIntoView({ behavior: 'smooth' }); };
  return (
    <header className="nav-blur fixed inset-x-0 top-0 z-40 border-b border-[#18264e]/10">
      <div className="section-wrap flex h-[74px] items-center justify-between">
        <button type="button" onClick={() => navigate('#top')} aria-label="Radiant home" data-testid="button-home">
          <img src={asset('logol.png')} alt="Radiant Coaching Classes" className="w-[143px] sm:w-[170px]" />
        </button>
        <nav className="hidden items-center gap-7 lg:flex">
          {links.map(([label, href]) => <button key={href} type="button" onClick={() => navigate(href)} className="text-[12px] font-bold uppercase tracking-[.08em] text-[#18264e]/70 transition-colors hover:text-[#df124f]" data-testid={`link-${label.toLowerCase().replaceAll(' ', '-')}`}>{label}</button>)}
        </nav>
        <div className="hidden items-center gap-3 sm:flex">
          <a href="tel:+919876543210" className="flex items-center gap-2 text-[12px] font-bold text-[#18264e]" data-testid="link-call"><Phone size={14} /> +91 98765 43210</a>
          <button type="button" onClick={onEnquire} className="rounded-full bg-[#df124f] px-5 py-3 text-[12px] font-bold uppercase tracking-[.08em] text-[#fffdf8] transition-transform hover:-translate-y-0.5" data-testid="button-open-enquiry">Talk to us <ArrowRight className="ml-1 inline" size={14} /></button>
        </div>
        <button type="button" onClick={() => setOpen(!open)} className="rounded-full bg-[#18264e] p-3 text-[#fffdf8] sm:hidden" aria-label="Open menu" data-testid="button-mobile-menu">{open ? <X size={19} /> : <Menu size={19} />}</button>
      </div>
      {open && <div className="border-t border-[#18264e]/10 bg-[#f9f4e9] px-5 pb-5 pt-3 sm:hidden">
        {links.map(([label, href]) => <button key={href} type="button" onClick={() => navigate(href)} className="block w-full border-b border-[#18264e]/10 py-3 text-left text-sm font-bold text-[#18264e]" data-testid={`mobile-link-${label.toLowerCase().replaceAll(' ', '-')}`}>{label}</button>)}
        <button type="button" onClick={() => { setOpen(false); onEnquire(); }} className="mt-4 w-full rounded-full bg-[#df124f] py-3 text-sm font-bold text-[#fffdf8]" data-testid="mobile-button-enquiry">Book a counselling call</button>
      </div>}
    </header>
  );
}

function Hero({ onEnquire }: { onEnquire: () => void }) {
  return <section id="top" className="hero-grid hero-rule relative min-h-[670px] pt-[74px] text-[#fffdf8]">
    <div className="section-wrap relative grid min-h-[596px] items-center gap-12 py-20 lg:grid-cols-[1.05fr_.95fr]">
      <div className="relative z-10">
        <Reveal><div className="eyebrow text-[#ffc928]">Pune's focused launchpad</div></Reveal>
        <Reveal delay="delay-1"><h1 className="font-display mt-7 max-w-[700px] text-[clamp(3.5rem,8vw,7.6rem)] font-bold leading-[.88] tracking-[-.065em]">Study with <span className="text-[#ffc928]">intent.</span><br />Arrive with <span className="text-[#f0376d]">options.</span></h1></Reveal>
        <Reveal delay="delay-2"><p className="mt-8 max-w-[510px] text-[16px] leading-7 text-[#fffdf8]/75 sm:text-[18px]">Serious preparation for JEE, NEET, MHT-CET and the years that shape them. Small batches. Big clarity.</p></Reveal>
        <Reveal delay="delay-3"><div className="mt-9 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
          <button type="button" onClick={onEnquire} className="group rounded-full bg-[#ffc928] px-7 py-4 text-sm font-bold text-[#101d42] transition-all hover:-translate-y-1 hover:bg-[#fffdf8]" data-testid="button-hero-enquiry">Find your programme <ArrowRight className="ml-2 inline transition-transform group-hover:translate-x-1" size={16} /></button>
          <button type="button" onClick={() => document.querySelector('#programs')?.scrollIntoView({ behavior: 'smooth' })} className="flex items-center gap-2 text-sm font-bold text-[#fffdf8] underline decoration-[#f0376d] decoration-2 underline-offset-8" data-testid="button-explore-programs">Explore programmes <ArrowDownRight size={17} /></button>
        </div></Reveal>
      </div>
      <div className="relative hidden h-[430px] lg:block">
        <div className="scribble right-5 top-2 h-[360px] w-[360px] opacity-70"></div><div className="scribble right-20 top-16 h-[250px] w-[250px] opacity-50"></div>
        <div className="floaty absolute right-5 top-6 w-[270px] rotate-[-6deg] border-[10px] border-[#fffdf8] bg-[#fffdf8] shadow-[18px_18px_0_#df124f]"><img src={asset('radient-r-3.webp')} alt="Radiant students celebrating together" className="h-[265px] w-full object-cover" /><div className="p-3 text-[#18264e]"><div className="font-display text-2xl font-bold">Your people.</div><div className="text-[11px] font-bold uppercase tracking-[.12em] text-[#df124f]">Your place to grow.</div></div></div>
        <div className="absolute bottom-7 left-8 z-10 rotate-[7deg] bg-[#ffc928] px-5 py-4 text-[#101d42] shadow-[8px_8px_0_#101d42]"><div className="font-display text-3xl font-bold leading-none">1:1</div><div className="mt-1 text-[10px] font-bold uppercase tracking-[.13em]">Mentor check-ins</div></div>
      </div>
    </div>
    <div className="absolute bottom-0 left-0 right-0 overflow-hidden border-t border-[#fffdf8]/15 bg-[#101d42]/80 py-3">
      <div className="marquee-track flex w-max items-center gap-12 whitespace-nowrap text-[11px] font-bold uppercase tracking-[.24em] text-[#fffdf8]/60"><span>Concepts over cramming</span><span className="text-[#ffc928]">/</span><span>Progress over pressure</span><span className="text-[#f0376d]">/</span><span>Questions welcome here</span><span className="text-[#ffc928]">/</span><span>Concepts over cramming</span><span className="text-[#ffc928]">/</span><span>Progress over pressure</span><span className="text-[#f0376d]">/</span><span>Questions welcome here</span></div>
    </div>
  </section>;
}

function Programs({ onEnquire }: { onEnquire: (program?: string) => void }) {
  const [active, setActive] = useState('jee');
  const current = programs.find((program) => program.id === active) ?? programs[0];
  return <section id="programs" className="relative bg-[#f9f4e9] py-24 sm:py-32">
    <div className="section-wrap">
      <Reveal><div className="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><div className="eyebrow">Choose your runway</div><h2 className="font-display mt-5 max-w-[630px] text-5xl font-bold leading-[.94] tracking-[-.05em] text-[#18264e] sm:text-7xl">One goal.<br /><span className="text-[#df124f]">Your way</span> there.</h2></div><p className="max-w-[285px] text-sm leading-6 text-[#18264e]/65">Each programme is built around a different kind of ambition. Find the one that sounds like you.</p></div></Reveal>
      <div className="mt-14 grid gap-8 lg:grid-cols-[.85fr_1.15fr]">
        <div className="flex flex-col gap-2">
          {programs.map((program, index) => <button key={program.id} type="button" onClick={() => setActive(program.id)} className={`program-card group flex items-center justify-between border-b border-[#18264e]/15 px-1 py-5 text-left ${active === program.id ? 'text-[#df124f]' : 'text-[#18264e]'}`} data-testid={`button-program-${program.id}`}><span className="flex items-center gap-4"><span className="font-mono text-[10px] opacity-45">0{index + 1}</span><span className="font-display text-2xl font-bold sm:text-3xl">{program.title}</span></span><ArrowRight size={20} className={`transition-transform ${active === program.id ? 'translate-x-1' : 'opacity-30 group-hover:translate-x-1'}`} /></button>)}
          <button type="button" onClick={() => onEnquire(current.title)} className="mt-5 self-start text-sm font-bold text-[#18264e] underline decoration-[#df124f] decoration-2 underline-offset-8" data-testid="button-program-enquiry">Ask about {current.title} <ArrowRight className="ml-1 inline" size={15} /></button>
        </div>
        <div className="relative overflow-hidden rounded-[2px] bg-[#18264e] p-2 shadow-[12px_12px_0_#ffc928]">
          <div className="relative min-h-[400px] overflow-hidden sm:min-h-[450px]"><img src={current.image} alt={`${current.title} preparation`} className="absolute inset-0 h-full w-full object-cover opacity-75 transition-opacity duration-300" /><div className="absolute inset-0 bg-gradient-to-t from-[#101d42] via-[#101d42]/15 to-transparent"></div><div className="absolute inset-x-0 bottom-0 p-7 text-[#fffdf8] sm:p-10"><div className="mb-2 text-[11px] font-bold uppercase tracking-[.2em]" style={{ color: current.accent }}>{current.kicker}</div><div className="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><h3 className="font-display text-5xl font-bold tracking-[-.05em] sm:text-6xl">{current.title}</h3><p className="mt-3 max-w-[440px] text-sm leading-6 text-[#fffdf8]/75">{current.detail}</p></div><div className="shrink-0 rounded-full border border-[#fffdf8]/30 px-4 py-2 text-[11px] font-bold uppercase tracking-[.1em]">{current.duration}</div></div><div className="mt-6 flex flex-wrap gap-x-5 gap-y-2 border-t border-[#fffdf8]/20 pt-4">{current.points.map((point) => <span key={point} className="flex items-center gap-2 text-[11px] font-bold text-[#fffdf8]/80"><Check size={13} color={current.accent} />{point}</span>)}</div></div></div>
        </div>
      </div>
    </div>
  </section>;
}

function Method() {
  const methods = [{ no: '01', title: 'See the whole map', body: 'A diagnostic start tells us what is solid, what is shaky and what deserves your next hour.' }, { no: '02', title: 'Make a rhythm', body: 'Classes, practice, tests and recovery — arranged into a week you can repeat, not just admire.' }, { no: '03', title: 'Use the feedback', body: 'Every test ends with a conversation. We turn mistakes into the exact plan for the next one.' }];
  return <section id="about" className="bg-[#ffc928] py-24 sm:py-28"><div className="section-wrap"><Reveal><div className="grid gap-10 lg:grid-cols-[.85fr_1.15fr]"><div><div className="eyebrow text-[#df124f]">The Radiant method</div><h2 className="font-display mt-5 max-w-[430px] text-5xl font-bold leading-[.92] tracking-[-.06em] text-[#101d42] sm:text-7xl">Less noise.<br /><span className="text-[#df124f]">More next steps.</span></h2><p className="mt-7 max-w-[330px] text-sm leading-6 text-[#101d42]/70">There is no one-size-fits-all student. So we build preparation around the person sitting in front of us.</p><button type="button" onClick={() => document.querySelector('#student-life')?.scrollIntoView({ behavior: 'smooth' })} className="mt-8 flex items-center gap-2 rounded-full bg-[#101d42] px-6 py-3 text-sm font-bold text-[#fffdf8] hover:bg-[#df124f]" data-testid="button-see-life">See life at Radiant <ArrowRight size={16} /></button></div><div className="divide-y divide-[#101d42]/20 border-y border-[#101d42]/20">{methods.map((method) => <div key={method.no} className="grid gap-3 py-7 sm:grid-cols-[70px_1fr]"><span className="font-mono text-sm font-bold text-[#df124f]">{method.no}</span><div><h3 className="font-display text-2xl font-bold text-[#101d42]">{method.title}</h3><p className="mt-2 max-w-[460px] text-sm leading-6 text-[#101d42]/65">{method.body}</p></div></div>)}</div></div></Reveal></div></section>;
}

function Results() {
  return <section id="results" className="relative overflow-hidden bg-[#fffdf8] py-24 sm:py-32"><div className="absolute inset-0 bg-[url('/assets/ourresultbg.jpg')] bg-cover bg-center opacity-30"></div><div className="section-wrap relative"><Reveal><div className="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><div className="eyebrow">Proof, not promises</div><h2 className="font-display mt-5 max-w-[630px] text-5xl font-bold leading-[.94] tracking-[-.05em] text-[#18264e] sm:text-7xl">Small wins.<br /><span className="text-[#df124f]">Big momentum.</span></h2></div><p className="max-w-[270px] text-sm leading-6 text-[#18264e]/65">The score matters. So does the student who learned how to earn it.</p></div></Reveal><div className="mt-14 grid gap-4 sm:grid-cols-3"><Reveal delay="delay-1"><div className="bg-[#18264e] p-7 text-[#fffdf8] sm:p-9"><div className="font-display text-6xl font-bold text-[#ffc928]">98.6</div><div className="mt-3 text-sm font-bold">Highest MHT-CET percentile</div><div className="mt-10 flex items-center gap-2 text-[11px] uppercase tracking-[.14em] text-[#fffdf8]/55"><Target size={15} /> 2024 result</div></div></Reveal><Reveal delay="delay-2"><div className="bg-[#df124f] p-7 text-[#fffdf8] sm:p-9"><div className="font-display text-6xl font-bold text-[#ffc928]">47</div><div className="mt-3 text-sm font-bold">students who made their next list</div><div className="mt-10 flex items-center gap-2 text-[11px] uppercase tracking-[.14em] text-[#fffdf8]/65"><CircleCheck size={15} /> And counting</div></div></Reveal><Reveal delay="delay-3"><div className="border border-[#18264e]/15 bg-[#f9f4e9] p-7 sm:p-9"><div className="font-display text-6xl font-bold text-[#18264e]">1:1</div><div className="mt-3 text-sm font-bold text-[#18264e]">mentor check-in rhythm</div><div className="mt-10 flex items-center gap-2 text-[11px] uppercase tracking-[.14em] text-[#18264e]/55"><Clock3 size={15} /> Built into every batch</div></div></Reveal></div></div></section>;
}

function Stories() {
  const [index, setIndex] = useState(0);
  const story = stories[index];
  return <section className="bg-[#101d42] py-24 text-[#fffdf8] sm:py-28"><div className="section-wrap grid items-center gap-14 lg:grid-cols-[.8fr_1.2fr]"><Reveal><div><div className="eyebrow text-[#ffc928]">Straight from the batch</div><h2 className="font-display mt-5 max-w-[390px] text-5xl font-bold leading-[.94] tracking-[-.06em] sm:text-6xl">The work feels different when you’re not doing it <span className="text-[#ffc928]">alone.</span></h2><div className="mt-10 flex gap-2"><button type="button" onClick={() => setIndex((index - 1 + stories.length) % stories.length)} className="rounded-full border border-[#fffdf8]/30 p-3 hover:bg-[#fffdf8] hover:text-[#101d42]" aria-label="Previous story" data-testid="button-previous-story"><ChevronLeft size={18} /></button><button type="button" onClick={() => setIndex((index + 1) % stories.length)} className="rounded-full border border-[#fffdf8]/30 p-3 hover:bg-[#fffdf8] hover:text-[#101d42]" aria-label="Next story" data-testid="button-next-story"><ChevronRight size={18} /></button></div></div></Reveal><Reveal delay="delay-1"><div className="relative border-l-2 border-[#df124f] pl-7 sm:pl-12"><Quote className="absolute -left-5 -top-5 fill-[#df124f] text-[#df124f]" size={40} /><p className="max-w-[660px] font-display text-3xl font-medium leading-tight tracking-[-.03em] sm:text-5xl">“{story.quote}”</p><div className="mt-9 flex items-center gap-4"><div className="flex h-12 w-12 items-center justify-center rounded-full bg-[#ffc928] font-display font-bold text-[#101d42]">{story.initials}</div><div><div className="font-bold">{story.name}</div><div className="text-sm text-[#fffdf8]/55">{story.result}</div></div></div><div className="mt-10 flex gap-1">{stories.map((item, storyIndex) => <button type="button" key={item.name} onClick={() => setIndex(storyIndex)} aria-label={`View ${item.name}'s story`} className={`h-1.5 transition-all ${storyIndex === index ? 'w-10 bg-[#ffc928]' : 'w-5 bg-[#fffdf8]/25'}`} data-testid={`button-story-${storyIndex}`} />)}</div></div></Reveal></div></section>;
}

function StudentLife() {
  const images = [{ src: 'radient-r-2.webp', label: 'Mentors in the mix', cls: 'col-span-2 row-span-2' }, { src: 'gallary-r-1.webp', label: 'A room full of possibility', cls: '' }, { src: 'galllary-r-.webp', label: 'Celebrate the small wins', cls: '' }, { src: 'gallaray-r-4.webp', label: 'Learning beyond the classroom', cls: 'col-span-2' }];
  return <section id="student-life" className="relative bg-[#182d68] bg-[url('/assets/gallary-bg.png')] bg-cover bg-right py-24 text-[#fffdf8] sm:py-32"><div className="section-wrap"><Reveal><div className="flex flex-col justify-between gap-6 sm:flex-row sm:items-end"><div><div className="eyebrow text-[#ffc928]">Student life</div><h2 className="font-display mt-5 max-w-[610px] text-5xl font-bold leading-[.94] tracking-[-.06em] sm:text-7xl">A serious place<br />with <span className="text-[#ffc928]">good energy.</span></h2></div><p className="max-w-[280px] text-sm leading-6 text-[#fffdf8]/65">Because the best preparation rooms make space for questions, friendships and a little joy between chapters.</p></div></Reveal><div className="mt-12 grid auto-rows-[150px] grid-cols-2 gap-3 sm:auto-rows-[190px] sm:grid-cols-4"><Reveal className={images[0].cls}><div className="gallery-tile group relative h-full overflow-hidden"><img src={asset(images[0].src)} alt={images[0].label} className="h-full w-full object-cover" /><div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#101d42]/90 to-transparent p-4 pt-12 text-xs font-bold">{images[0].label}</div></div></Reveal>{images.slice(1).map((image, imageIndex) => <Reveal key={image.src} delay={`delay-${imageIndex + 1}`} className={image.cls}><div className="gallery-tile group relative h-full overflow-hidden"><img src={asset(image.src)} alt={image.label} className="h-full w-full object-cover" /><div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#101d42]/90 to-transparent p-3 pt-10 text-[11px] font-bold">{image.label}</div></div></Reveal>)}</div></div></section>;
}

function EnquiryModal({ program, onClose }: { program?: string; onClose: () => void }) {
  const [sent, setSent] = useState(false);
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [selected, setSelected] = useState(program ?? 'IIT-JEE');
  const submit = (event: FormEvent) => { event.preventDefault(); setSent(true); };
  useEffect(() => { document.body.style.overflow = 'hidden'; return () => { document.body.style.overflow = ''; }; }, []);
  return <div className="modal-backdrop fixed inset-0 z-50 flex items-end justify-center bg-[#101d42]/70 p-0 backdrop-blur-sm sm:items-center sm:p-5" role="dialog" aria-modal="true" aria-label="Book a counselling call" data-testid="modal-enquiry"><div className="modal-card relative max-h-[92dvh] w-full overflow-y-auto bg-[#f9f4e9] p-7 shadow-[10px_10px_0_#ffc928] sm:max-w-[570px] sm:p-10"><button type="button" onClick={onClose} className="absolute right-5 top-5 rounded-full p-2 text-[#18264e] hover:bg-[#18264e]/10" aria-label="Close enquiry" data-testid="button-close-enquiry"><X size={20} /></button>{sent ? <div className="py-12 text-center"><div className="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#ffc928] text-[#18264e]"><Check size={30} /></div><h2 className="font-display mt-6 text-4xl font-bold text-[#18264e]">You’re on the list.</h2><p className="mx-auto mt-4 max-w-[360px] text-sm leading-6 text-[#18264e]/65">Thanks{ name ? `, ${name}` : '' }. A Radiant counsellor will call you shortly to understand the right next step.</p><button type="button" onClick={onClose} className="mt-8 rounded-full bg-[#df124f] px-6 py-3 text-sm font-bold text-[#fffdf8]" data-testid="button-close-success">Back to Radiant</button></div> : <><div className="eyebrow">Let’s make a plan</div><h2 className="font-display mt-5 max-w-[410px] text-4xl font-bold leading-[.95] tracking-[-.05em] text-[#18264e] sm:text-5xl">A clearer next step starts with a <span className="text-[#df124f]">conversation.</span></h2><p className="mt-4 max-w-[420px] text-sm leading-6 text-[#18264e]/65">Tell us a little about the student. We’ll share the right batch, schedule and honest answer to your questions.</p><form onSubmit={submit} className="mt-8 space-y-4"><label className="block"><span className="mb-2 block text-[11px] font-bold uppercase tracking-[.12em] text-[#18264e]/65">Student / parent name</span><input required value={name} onChange={(event) => setName(event.target.value)} className="w-full border-b-2 border-[#18264e]/20 bg-transparent px-0 py-3 text-base outline-none transition-colors focus:border-[#df124f]" placeholder="Your name" data-testid="input-enquiry-name" /></label><label className="block"><span className="mb-2 block text-[11px] font-bold uppercase tracking-[.12em] text-[#18264e]/65">Mobile number</span><input required value={phone} onChange={(event) => setPhone(event.target.value)} className="w-full border-b-2 border-[#18264e]/20 bg-transparent px-0 py-3 text-base outline-none transition-colors focus:border-[#df124f]" placeholder="+91 98765 43210" type="tel" data-testid="input-enquiry-phone" /></label><label className="block"><span className="mb-2 block text-[11px] font-bold uppercase tracking-[.12em] text-[#18264e]/65">I’m exploring</span><div className="relative"><select value={selected} onChange={(event) => setSelected(event.target.value)} className="w-full appearance-none border-b-2 border-[#18264e]/20 bg-transparent px-0 py-3 text-base outline-none focus:border-[#df124f]" data-testid="select-enquiry-program">{programs.map((item) => <option key={item.id}>{item.title}</option>)}</select><ChevronDown className="pointer-events-none absolute right-1 top-3" size={18} /></div></label><button type="submit" className="mt-4 flex w-full items-center justify-center gap-2 rounded-full bg-[#df124f] py-4 text-sm font-bold text-[#fffdf8] transition-transform hover:-translate-y-0.5" data-testid="button-submit-enquiry">Request a counselling call <ArrowRight size={16} /></button><p className="text-center text-[10px] text-[#18264e]/45">No spam. Just one useful conversation.</p></form></>}</div></div>;
}

function Footer({ onEnquire }: { onEnquire: () => void }) {
  return <footer className="bg-[#101d42] py-14 text-[#fffdf8]"><div className="section-wrap"><div className="flex flex-col justify-between gap-10 border-b border-[#fffdf8]/15 pb-12 sm:flex-row"><div><img src={asset('logol.png')} alt="Radiant Coaching Classes" className="w-[170px] brightness-0 invert" /><p className="mt-5 max-w-[260px] text-sm leading-6 text-[#fffdf8]/55">Focused preparation for students who want more choices when it counts.</p></div><div className="grid grid-cols-2 gap-x-12 gap-y-4 text-sm"><button type="button" onClick={() => document.querySelector('#programs')?.scrollIntoView({ behavior: 'smooth' })} className="text-left text-[#fffdf8]/70 hover:text-[#ffc928]" data-testid="footer-link-programs">Programmes</button><button type="button" onClick={() => document.querySelector('#results')?.scrollIntoView({ behavior: 'smooth' })} className="text-left text-[#fffdf8]/70 hover:text-[#ffc928]" data-testid="footer-link-results">Results</button><button type="button" onClick={() => document.querySelector('#student-life')?.scrollIntoView({ behavior: 'smooth' })} className="text-left text-[#fffdf8]/70 hover:text-[#ffc928]" data-testid="footer-link-life">Student life</button><button type="button" onClick={onEnquire} className="text-left text-[#fffdf8]/70 hover:text-[#ffc928]" data-testid="footer-link-contact">Contact us</button></div></div><div className="flex flex-col justify-between gap-5 pt-7 text-[11px] text-[#fffdf8]/45 sm:flex-row"><span>© 2024 Radiant Coaching Classes · Pune</span><div className="flex items-center gap-4"><a href="https://instagram.com" aria-label="Instagram" data-testid="link-instagram"><Instagram size={16} /></a><a href="https://youtube.com" aria-label="YouTube" data-testid="link-youtube"><Youtube size={17} /></a><a href="tel:+919876543210" className="flex items-center gap-2" data-testid="footer-link-phone"><Phone size={14} /> +91 98765 43210</a></div></div></div></footer>;
}

function Home() {
  const [enquiry, setEnquiry] = useState<{ open: boolean; program?: string }>({ open: false });
  const openEnquiry = (program?: string) => setEnquiry({ open: true, program });
  return <div className="site-shell min-h-[100dvh]"><Header onEnquire={() => openEnquiry()} /><main><Hero onEnquire={() => openEnquiry()} /><Programs onEnquire={openEnquiry} /><Method /><Results /><Stories /><StudentLife /><section className="bg-[#df124f] py-16 text-[#fffdf8] sm:py-20"><div className="section-wrap flex flex-col items-start justify-between gap-8 sm:flex-row sm:items-center"><div><div className="font-display text-4xl font-bold leading-none tracking-[-.05em] sm:text-6xl">Ready to find your<br /><span className="text-[#ffc928]">next right step?</span></div><p className="mt-4 text-sm text-[#fffdf8]/70">Bring your questions. Leave with a plan.</p></div><button type="button" onClick={() => openEnquiry()} className="flex items-center gap-3 rounded-full bg-[#ffc928] px-7 py-4 text-sm font-bold text-[#101d42] transition-transform hover:-translate-y-1" data-testid="button-final-enquiry">Start a conversation <ArrowRight size={17} /></button></div></section></main><Footer onEnquire={() => openEnquiry()} />{enquiry.open && <EnquiryModal program={enquiry.program} onClose={() => setEnquiry({ open: false })} />}<a href="https://wa.me/919876543210" className="fixed bottom-5 right-5 z-30 flex items-center gap-2 rounded-full bg-[#25a779] px-4 py-3 text-xs font-bold text-[#fffdf8] shadow-lg transition-transform hover:-translate-y-1" data-testid="link-whatsapp"><MessageCircle size={17} /> <span className="hidden sm:inline">Quick WhatsApp</span></a></div>;
}

function Router() {
  return <RoutedErrorBoundary><Switch><Route path="/" component={Home} /><Route component={NotFound} /></Switch></RoutedErrorBoundary>;
}

function RoutedErrorBoundary({ children }: { children: ReactNode }) {
  const [location] = useLocation();
  return <ErrorBoundary resetKey={location}>{children}</ErrorBoundary>;
}

function App() {
  return <QueryClientProvider client={queryClient}><TooltipProvider><WouterRouter base={import.meta.env.BASE_URL.replace(/\/$/, '')}><Router /></WouterRouter><Toaster /></TooltipProvider></QueryClientProvider>;
}

export default App;